<?php
require "db.php";

if (!isset($_GET['id'])) {
    die("ID mancante");
}

$id = intval($_GET['id']);

/* 1. Prendo il file dal DB */
$stmt = $conn->prepare("SELECT percorso FROM files WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("File non trovato");
}

$file = $result->fetch_assoc();
$path = $file['percorso'];

/* 2. Cancello file dal server */
if (file_exists($path)) {
    unlink($path);
}

/* 3. Cancello dal database */
$stmt = $conn->prepare("DELETE FROM files WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

/* 4. Ritorno alla pagina */
header("Location: file.php");
exit;