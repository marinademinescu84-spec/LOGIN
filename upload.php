<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    die("Sessione rifiutata per mancanza di credenziali.");
}

require "db.php";

$userId = $_SESSION['user_id'];

$userId = $_SESSION['user_id'];

/*
|------------------------------------------------------------------
| BLOCCO: UTENTE PUÒ CARICARE SOLO 1 AUDIO
|------------------------------------------------------------------
*/

$stmt = $conn->prepare("SELECT id FROM files WHERE utente_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    die("Hai già inviato il tuo audio.");
}

if (!isset($_FILES['file'])) {
    die("Nessun file rilevato.");
}

$file = $_FILES['file'];

$nomeOriginale = $file['name'];
$tmpPath        = $file['tmp_name'];
$size           = $file['size'];
$error          = $file['error'];

if ($error !== 0) {
    die("Errore durante il caricamento del file audio.");
}

/*
|--------------------------------------------------------------------------
| CONTROLLO ESTENSIONE
|--------------------------------------------------------------------------
*/

$tipo = strtolower(pathinfo($nomeOriginale, PATHINFO_EXTENSION));

$estensioniConsentite = ['mp3'];

if (!in_array($tipo, $estensioniConsentite)) {

    die("
        Errore di convalida:
        sono consentiti solamente file MP3.
    ");
}

/*
|--------------------------------------------------------------------------
| CONTROLLO DIMENSIONE MAX 50MB
|--------------------------------------------------------------------------
*/

if ($size > (50 * 1024 * 1024)) {

    die("
        Errore:
        il file supera il limite massimo di 50MB.
    ");
}

/*
|--------------------------------------------------------------------------
| CARTELLA UPLOAD
|--------------------------------------------------------------------------
*/

$uploadDir = "uploads/";

if (!file_exists($uploadDir)) {

    mkdir($uploadDir, 0777, true);
}

/*
|--------------------------------------------------------------------------
| NOME FILE SICURO
|--------------------------------------------------------------------------
*/

$nomeFinale =
    "AUDIO_" .
    uniqid() .
    "_" .
    time() .
    "." .
    $tipo;

$destinazione = $uploadDir . $nomeFinale;

/*
|--------------------------------------------------------------------------
| SPOSTAMENTO FILE
|--------------------------------------------------------------------------
*/

if (move_uploaded_file($tmpPath, $destinazione)) {

    $stmt = $conn->prepare("
        INSERT INTO files
        (
            utente_id,
            nome_file,
            tipo,
            dimensione,
            percorso,
            data_upload
        )
        VALUES
        (
            ?, ?, ?, ?, ?, NOW()
        )
    ");

    $stmt->bind_param(
        "issis",
        $userId,
        $nomeOriginale,
        $tipo,
        $size,
        $destinazione
    );

    $stmt->execute();

    header("Location: file.php");
    exit;

} else {

    die("
        Il server non è riuscito
        a salvare il file audio.
    ");
}
?>