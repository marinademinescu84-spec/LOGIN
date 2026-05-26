<?php
// 1. INIZIALIZZAZIONE DELLA SESSIONE E SICUREZZA
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se l'utente non è loggato, rimandalo alla pagina di login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}

// 2. CONNESSIONE AL DATABASE E CONFIGURAZIONE
require "db.php";
$activePage = 'dashboard';
$userId = $_SESSION['user_id'];

// Attivazione segnalazione errori (utile in locale per i test)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 3. RECUPERO DATI UTENTE LOGGATO
$stmt = $conn->prepare("SELECT nome, cognome, email, role FROM utenti WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Se l'utente non esiste più nel DB, distruggi la sessione e disconnetti
if (!$user) {
    session_destroy();
    header("Location: login_page.php");
    exit();
}

// Aggiorna il ruolo in sessione per sicurezza
$_SESSION['role'] = $user['role'];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Account - Concorso Video AIDO</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <?php if (file_exists("includes/header.php")) { require "includes/header.php"; } ?>

    <div class="main-wrapper">
        
    <div class="sidebar-wrapper">
            <?php if (file_exists("includes/sidebar.php")) { require "includes/sidebar.php"; } ?>
        </div>

        <main class="content">
            <div class="page-header">
                <h2>Benvenuto 👋 <?php echo htmlspecialchars(($user['nome'] ?? '') . ' ' . ($user['cognome'] ?? '')); ?></h2>
                <p>Partecipa al concorso di sensibilizzazione AIDO. Carica il tuo video e segui le linee guida del bando.</p>
            </div>

          <div class="cards">

    <div class="card">
        <div class="card-icon">🎬</div>
        <h3>Invio File Concorso</h3>
        <p>Accedi all’area upload per inviare il tuo file ufficiale per il concorso AIDO.</p>

        <a href="file.php" class="btn aido-primary w-full mt-10">
            Vai all’upload →
        </a>
    </div>

    <div class="card">
        <div class="card-icon">📁</div>
        <h3>Specifiche Tecniche</h3>
        <p>
            Durata massima: 60 secondi<br>
            Formati: MP3<br>
            Dimensione max: 50MB
        </p>

        <span class="badge aido-red">Regole ufficiali AIDO</span>
    </div>

    <div class="card">
        <div class="card-icon">❤️</div>
        <h3>Tema 2026</h3>
        <p>
            Racconta il valore della donazione e della solidarietà attraverso il tuo contenuto creativo.
        </p>

        <span class="badge">Scadenza: 15 Giugno</span>
    </div>

</div>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator'): ?>
                <div class="admin-dashboard-zone">
                    <div class="card-admin-wrapper">
                        <div class="admin-badge-top">🛡️ ACCESSO RISERVATO ADMINISTRATOR</div>
                        <h3>Pannello di Controllo Gara</h3>
                        <p>Il tuo account ha i privilegi di amministratore. Da qui puoi gestire gli utenti, attivare gli account sospesi o liberare le email nel database locale.</p>
                        <a href="mini-admin.php" class="btn-admin-launch">
                            ⚙️ Apri Gestione Utenti Database
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </main>
    </div>

    <?php if (file_exists("includes/footer.php")) { require "includes/footer.php"; } ?>

</body>
</html>