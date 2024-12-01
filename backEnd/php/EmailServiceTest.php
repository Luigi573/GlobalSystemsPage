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

        
    }

    public function testValidateEmailFail()
    {
        $emailService = new EmailService();

        $this->assertFalse($emailService->validateEmail('invalid-email'));
    }



    public function testSendEmailSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

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
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        $emailService = $this->getMockBuilder(EmailService::class)
                            ->disableOriginalConstructor() 
                            ->onlyMethods(['sendEmail'])  
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
