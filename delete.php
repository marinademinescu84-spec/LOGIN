<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    die("Operazione non autorizzata.");
}

require "db.php";
$userId = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    die("Riferimento ID non pervenuto.");
}

$id = intval($_GET['id']);

/* 1. Controllo incrociato di sicurezza: il file deve appartenere a chi sta provando a cancellarlo */
$stmt = $conn->prepare("SELECT percorso FROM files WHERE id = ? AND utente_id = ?");
$stmt->bind_param("ii", $id, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Errore: Non disponi delle autorizzazioni necessarie per rimuovere questo file.");
}

$file = $result->fetch_assoc();
$path = $file['percorso'];

/* 2. Eliminazione del file video dal disco fisso */
if (file_exists($path)) {
    unlink($path);
}

/* 3. Rimozione della riga dalla tabella del Database */
$stmt = $conn->prepare("DELETE FROM files WHERE id = ? AND utente_id = ?");
$stmt->bind_param("ii", $id, $userId);
$stmt->execute();

header("Location: file.php");
exit;