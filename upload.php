<?php
require "db.php";
session_start();

if (!isset($_FILES['file'])) {
    die("Nessun file caricato");
}

$file = $_FILES['file'];

$nomeOriginale = $file['name'];
$tmpPath = $file['tmp_name'];
$size = $file['size'];
$error = $file['error'];

if ($error !== 0) {
    die("Errore upload file");
}

// cartella dove salvare i file
$uploadDir = "uploads/";

// crea nome unico per evitare sovrascritture
$nomeFinale = uniqid() . "_" . $nomeOriginale;

$destinazione = $uploadDir . $nomeFinale;

// sposta il file nel server
move_uploaded_file($tmpPath, $destinazione);

// tipo file
$tipo = pathinfo($nomeOriginale, PATHINFO_EXTENSION);

// salva nel database
$stmt = $conn->prepare("
    INSERT INTO files (nome_file, tipo, dimensione, percorso, data_upload)
    VALUES (?, ?, ?, ?, NOW())
");

$stmt->bind_param("ssis",
    $nomeOriginale,
    $tipo,
    $size,
    $destinazione
);

$stmt->execute();

header("Location: file.php");
exit;