<?php
// Attiva la visualizzazione degli errori PHP per vedere cosa non va su Localhost
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "db.php";

$nome = trim($_POST['nome'] ?? '');
$cognome = trim($_POST['cognome'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$email = trim($_POST['email'] ?? '');
$passwordRaw = $_POST['password'] ?? '';

if ($nome === '' || $cognome === '' || $email === '' || $passwordRaw === '') {
    exit("Dati obbligatori mancanti");
}

// Genera token di attivazione e cripta la password
$token = bin2hex(random_bytes(16));
$password = password_hash($passwordRaw, PASSWORD_DEFAULT);
$role = "user";

// Controllo duplicati: verifica se l'email esiste già
$checkEmail = $conn->prepare("SELECT id FROM utenti WHERE email = ?");
$checkEmail->bind_param("s", $email);
$checkEmail->execute();
if ($checkEmail->get_result()->num_rows > 0) {
    exit("Questa email è già registrata!");
}

$sql = "INSERT INTO utenti (nome, cognome, telefono, email, password, role, activation_token)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    exit("Errore di preparazione query: " . $conn->error);
}

$stmt->bind_param("sssssss", $nome, $cognome, $telefono, $email, $password, $role, $token);

if ($stmt->execute()) {

    // Modifica questo URL inserendo il percorso reale del tuo localhost se necessario (es. http://localhost/progetto/...)
  $link = "http://localhost/login/attiva.php?token=" . $token;
    $subject = "Attivazione account";

    $message = "
Ciao $nome,

la tua registrazione è avvenuta con successo.
Per attivare il tuo account clicca questo link:

$link

Grazie!
";

    // Includiamo l'invio mail isolandolo per evitare che un blocco SMTP blocchi la risposta AJAX
    if (file_exists("inviomail.php")) {
        include_once "inviomail.php";
        
        try {
            sendMail($email, $subject, $message);
            echo "OK";
        } catch (Exception $e) {
            // Se l'invio mail fallisce su Localhost, l'utente viene registrato comunque ma viene avvisato
            echo "OK_MA_MAIL_FALLITA (Utente salvato, ma errore invio SMTP: " . $e->getMessage() . ")";
        }
    } else {
        echo "OK_SENZA_MAIL (Utente creato, file inviomail.php mancante)";
    }

} else {
    echo "Errore inserimento Database SQL: " . $stmt->error;
}
?>