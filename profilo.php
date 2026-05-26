<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Controllo di sicurezza integrato
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit;
}

require "db.php";
$activePage = 'profilo';
$userId = $_SESSION['user_id'];

// Recuperiamo i dati dell'utente loggato usando l'ID di sessione sicuro
$sql = "SELECT nome, cognome, email, role FROM utenti WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Utente non trovato o sessione non valida");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Profilo Partecipante - Concorso AIDO</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>

<?php if (file_exists("includes/header.php")) { require "includes/header.php"; } ?>

<div class="main-wrapper">

     <div class="sidebar-wrapper">
            <?php if (file_exists("includes/sidebar.php")) { require "includes/sidebar.php"; } ?>
        </div>

    <main class="content">
        <div class="page-header">
            <h2>👤 Profilo Partecipante</h2>
            <p>I tuoi dati di iscrizione ufficiali al concorso AIDO.</p>
        </div>

      <div class="cards">

    <!-- CARD PROFILO -->
    <div class="card profile-card">

        <div class="avatar-big">
            <?= strtoupper(substr($user['nome'] ?? 'U', 0, 1) . substr($user['cognome'] ?? 'T', 0, 1)) ?>
        </div>

        <h3>
            <?= htmlspecialchars(($user['nome'] ?? '') . ' ' . ($user['cognome'] ?? '')) ?>
        </h3>

        <div class="badge aido-red">
            Iscritto al Concorso
        </div>

    </div>

    <!-- CARD DATI -->
    <div class="card">

        <div class="field">
            <label>Nome Completo</label>
            <div><?= htmlspecialchars(($user['nome'] ?? '') . ' ' . ($user['cognome'] ?? '')) ?></div>
        </div>

        <div class="field">
            <label>Email di Contatto</label>
            <div><?= htmlspecialchars($user['email'] ?? '') ?></div>
        </div>

        <div class="field">
            <label>Stato Account</label>
            <div style="color:#16a34a;font-weight:bold;display:flex;align-items:center;gap:6px;">
                <span class="material-symbols-outlined" style="font-size:18px;">verified</span>
                Verificato via Mail
            </div>
        </div>

    </div>

</div>
        </div>
    </main>
</div>

<?php if (file_exists("includes/footer.php")) { require "includes/footer.php"; } ?>

</body>
</html>