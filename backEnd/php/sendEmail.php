<?php

$inputName = $_POST["inputName"];
$inputEmail = $_POST["inputEmail"];
$inputPhone = $_POST["inputPhone"];
$inputService = $_POST["inputService"];
$inputRequestBody = $_POST["inputRequestBody"];


require "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->Username = "globalsystems.bot@gmail.com";
$mail->Password = "matrix81";

$mail->setFrom($inputEmail, $inputName);
$mail->addAddress("jorge@inti.com.mx");

$mail->Subject = "Solicitud de servicio";
$mail->Body = "Nuevo servicio solicitado:\n\n" .
              "Nombre: " . $inputName . "\n" .
              "Correo de remitente: " . $inputEmail . "\n" .
              "Teléfono: " . $inputPhone . "\n" .
              "Servicio solicitado: " . $inputService . "\n\n" .
              "Mensaje:\n" . $inputRequestBody;

$mail->Send();