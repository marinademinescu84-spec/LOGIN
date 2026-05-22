<?php
require "db.php";

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$passwordRaw = $_POST['password'] ?? '';

if ($username === '' || $email === '' || $passwordRaw === '') {
    exit("Dati vuoti");
}

$token = bin2hex(random_bytes(16));
$password = password_hash($passwordRaw, PASSWORD_DEFAULT);
$role = "user";

$sql = "INSERT INTO utenti (username, password, email, role, activation_token)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    exit("Errore prepare: " . $conn->error);
}

$stmt->bind_param("sssss", $username, $password, $email, $role, $token);

if ($stmt->execute()) {

    // link di attivazione
    $link = "http://www.AIDO.IT/attiva.php?token=" . $token;

    $subject = "Attivazione account";

    $message = "
    Ciao $username,

    la tua registrazione è avvenuta con successo.

    Per attivare il tuo account clicca questo link:

    $link

    Grazie per esserti registrato!
    ";

    $headers = "From: no-reply@tuosito.it\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    mail($email, $subject, $message, $headers);

    echo "OK";

} else {
    echo "Errore SQL: " . $stmt->error;
}
?>