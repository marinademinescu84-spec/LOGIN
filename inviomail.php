<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 1. Carichiamo i 3 file indispensabili che hai nella cartella phpmailer
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

// 2. Carichiamo le funzioni per leggere dal database se non sono già caricate
if (!function_exists('getConfig')) {
    require_once "funzioni.php"; 
}

function sendMail($email, $subject, $message) {
    global $conn; 
    
    $mail = new PHPMailer(true);

    try {
        // 3. Configurazione Server SMTP recuperata dal database
        $mail->isSMTP();
        $mail->Host       = getConfig($conn, 'smtp_host');
        $mail->SMTPAuth   = true;
        $mail->Username   = getConfig($conn, 'smtp_username');
        $mail->Password   = getConfig($conn, 'smtp_password');
        $mail->SMTPSecure = getConfig($conn, 'smtp_secure');
        $mail->Port       = getConfig($conn, 'smtp_port');

        // Mittente e Destinatario
        $mail->setFrom('test@mc2servizi.it', 'Marina');
        $mail->addAddress($email);

        // Contenuto Mail
        $mail->isHTML(false); 
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->CharSet = 'UTF-8';

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Lanciamo l'errore così ajax.php può intercettarlo e mostrartelo nell'alert
        throw new Exception($mail->ErrorInfo);
    }
}
?>
