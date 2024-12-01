<?php

namespace BackEnd;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header('Content-Type: application/json; charset=utf-8');

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    public function processEmailRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inputName = strip_tags(filter_input(INPUT_POST, 'inputName', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $inputEmail = filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_EMAIL);
            $inputPhone = strip_tags(filter_input(INPUT_POST, 'inputPhone', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $inputService = html_entity_decode(strip_tags(filter_input(INPUT_POST, 'inputService', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
            $inputRequestBody = html_entity_decode(strip_tags(filter_input(INPUT_POST, 'inputRequestBody', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));

            if ($this->validateEmail($inputEmail)) {
                $emailSent = $this->sendEmail($inputName, $inputEmail, $inputPhone, $inputService, $inputRequestBody);

                if ($emailSent) {
                    http_response_code(200);
                    echo json_encode(["success" => true, "message" => "Correo enviado con éxito."]);
                } else {
                    $this->sendErrorResponse(500, "Hubo un error al enviar el correo.");
                }
            }else{
                $this->sendErrorResponse(400, "Correo electrónico no válido.");
            }
        }else{
            $this->sendErrorResponse(405, "Método no permitido.");
        }
    }

    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) == true;
    }

    public function sendEmail($inputName, $inputEmail, $inputPhone, $inputService, $inputRequestBody){
        try {
            $mailer = new PHPMailer(true);
            $mailer->isSMTP();
            $mailer->SMTPAuth = true;
            $mailer->Host = 'smtp.gmail.com';
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mailer->Port = 587;
            $mailer->Username = 'globalsystems.bot@gmail.com';  
            $mailer->Password = 'xfcejvlaudzygyei'; 
            $mailer->setFrom('globalsystems.bot@gmail.com', 'Global Systems Bot');
            $mailer->addAddress('jorge@inti.com.mx'); 
            $mailer->addCC($inputEmail);  
            $mailer->Subject = 'Solicitud de servicio';
            $mailer->Body = "Nuevo servicio solicitado:\n\n" . 
                "Nombre: " . $inputName . "\n" . 
                "Correo de remitente: " . $inputEmail . "\n" . 
                "Teléfono: " . $inputPhone . "\n" . 
                "Servicio solicitado: " . $inputService . "\n\n" . 
                "Mensaje:\n" . $inputRequestBody;
            
            $mailer->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function sendErrorResponse($code, $message){
        http_response_code($code);
        echo json_encode(["success" => false, "message" => $message]);
    }
}

$emailService = new EmailService();
$emailService->processEmailRequest();

?>
