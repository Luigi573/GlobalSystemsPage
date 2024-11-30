<?php

namespace BackEnd;

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class EmailService
{
    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) == true;
    }

    public function sendEmail($inputName, $inputEmail, $inputPhone, $inputService, $inputRequestBody){
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->Username = 'globalsystems.bot@gmail.com';  
            $mail->Password = 'xfcejvlaudzygyei'; 
            $mail->setFrom('globalsystems.bot@gmail.com', 'Global Systems Bot');
            $mail->addAddress('jorge@inti.com.mx'); 
            $mail->addCC($inputEmail);  
            $mail->Subject = 'Solicitud de servicio';
            $mail->Body = "Nuevo servicio solicitado:\n\n" . 
                "Nombre: " . $inputName . "\n" . 
                "Correo de remitente: " . $inputEmail . "\n" . 
                "Teléfono: " . $inputPhone . "\n" . 
                "Servicio solicitado: " . $inputService . "\n\n" . 
                "Mensaje:\n" . $inputRequestBody;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar el correo: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function processEmailRequest()
    {
        $this->setHeaders();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inputName = strip_tags(filter_input(INPUT_POST, 'inputName', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $inputEmail = filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_EMAIL);
            $inputPhone = strip_tags(filter_input(INPUT_POST, 'inputPhone', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $inputService = html_entity_decode(strip_tags(filter_input(INPUT_POST, 'inputService', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
            $inputRequestBody = html_entity_decode(strip_tags(filter_input(INPUT_POST, 'inputRequestBody', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));

            error_log($inputEmail);

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

    private function setHeaders(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header('Content-Type: application/json; charset=utf-8');
    }

    private function sendErrorResponse($code, $message){
        http_response_code($code);
        echo json_encode(["success" => false, "message" => $message]);
    }
}

?>
