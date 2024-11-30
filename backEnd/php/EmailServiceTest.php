<?php

require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use PHPMailer\PHPMailer\PHPMailer;
use BackEnd\EmailService;

class EmailServiceTest extends TestCase
{
    public function testValidateEmail()
    {
        $emailService = new EmailService();

        $this->assertTrue($emailService->validateEmail('test@example.com'));

        $this->assertFalse($emailService->validateEmail('invalid-email'));
    }



    public function testSendEmailSuccess()
{
    $mockMailer = $this->createMock(PHPMailer::class);
    $mockMailer->expects($this->once())->method('send')->willReturn(true);

    $emailService = $this->getMockBuilder(EmailService::class)
                         ->disableOriginalConstructor()  
                         ->onlyMethods(['sendEmail'])  
                         ->getMock();
    $emailService->method('sendEmail')->willReturn(true);

    $result = $emailService->sendEmail(
        'John Doe', 
        'xavier.arian@gmail.com', 
        '1234567890', 
        'Service', 
        'Request Body'
    );

    $this->assertTrue($result);
}


    public function testSendEmailFailure()
    {
        $mockMailer = $this->createMock(PHPMailer::class);
        $mockMailer->expects($this->once())->method('send')->willThrowException(new Exception('Error'));

        $emailService = $this->getMockBuilder(EmailService::class)
                            ->disableOriginalConstructor()  // Prevent actual constructor from running
                            ->onlyMethods(['sendEmail'])  // Only mock the createMailerInstance method
                            ->getMock();
        $emailService->method('sendEmail')->willReturn(false);

        $result = $emailService->sendEmail(
            'John Doe', 
            'john@example.com', 
            '1234567890', 
            'Service', 
            'Request Body'
        );

        $this->assertFalse($result);
    }

    public function testProcessEmailRequestValid()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'inputName' => 'John Doe',
            'inputEmail' => 'xavier.arian@gmail.com',
            'inputPhone' => '1234567890',
            'inputService' => 'Service',
            'inputRequestBody' => 'Request Body',
        ];

        $emailService = $this->getMockBuilder(EmailService::class)
                             ->onlyMethods(['sendEmail'])
                             ->getMock();
        $emailService->method('sendEmail')->willReturn(true);

        ob_start();
        $emailService->processEmailRequest();
        $output = ob_get_clean();

        $this->assertJsonStringEqualsJsonString(
            json_encode(["success" => true, "message" => "Correo enviado con éxito."]),
            $output
        );
    }

    public function testProcessEmailRequestInvalidEmail()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'inputName' => 'John Doe',
            'inputEmail' => 'invalid-email',
            'inputPhone' => '1234567890',
            'inputService' => 'Service',
            'inputRequestBody' => 'Request Body',
        ];

        $emailService = new EmailService();

        ob_start();
        $emailService->processEmailRequest();
        $output = ob_get_clean();

        $this->assertJsonStringEqualsJsonString(
            json_encode(["success" => false, "message" => "Correo electrónico no válido."]),
            $output
        );
        $this->assertEquals(400, http_response_code());
    }

    public function testProcessEmailRequestWrongMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $emailService = new EmailService();

        ob_start();
        $emailService->processEmailRequest();
        $output = ob_get_clean();

        $this->assertJsonStringEqualsJsonString(
            json_encode(["success" => false, "message" => "Método no permitido."]),
            $output
        );
        $this->assertEquals(405, http_response_code());
    }
}
