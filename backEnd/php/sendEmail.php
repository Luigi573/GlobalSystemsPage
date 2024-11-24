<?php

// CORS headers to allow AJAX requests from other domains
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Type: application/json; charset=utf-8");
//header('Content-Type: text/html; charset=utf-8');


error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $inputName = strip_tags(filter_input(INPUT_POST, 'inputName', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $inputEmail = filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_EMAIL);
    $inputPhone = strip_tags(filter_input(INPUT_POST, 'inputPhone', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $inputService = filter_input(INPUT_POST, 'inputService', FILTER_SANITIZE_NUMBER_INT);
    $inputRequestBody = strip_tags(filter_input(INPUT_POST, 'inputRequestBody', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    // 4. Validate the email
    if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        error_log("El correo no es válido.");
        exit;
    }

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
        http_response_code(200);
        
    } catch (Exception $e) {
        http_response_code(500); 
        echo json_encode(["success" => false, "message" => "Hubo un error al enviar el correo."]);
        error_log("Error al enviar el correo: {$mail->ErrorInfo}");
    }
} else {
    http_response_code(405);  
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    error_log("Método no permitido");
}
?>
