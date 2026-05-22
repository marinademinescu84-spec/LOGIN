<?php
require "includes/auth.php";
require "db.php";
$activePage = 'impostazioni';


$username = $_SESSION['username'] ?? '';

$stmt = $conn->prepare("SELECT username, email, role FROM utenti WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$initials = strtoupper(substr($user['username'], 0, 2));
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Impostazioni</title>
<link rel="stylesheet" href="index.css">
</head>

<body>

<?php require "includes/header.php"; ?>

<div class="container">

<?php require "includes/sidebar.php"; ?>

<main class="content">

<!-- HEADER -->
<div class="page-header">
    <h2>⚙️ Impostazioni</h2>
    <p>Gestisci account, sicurezza e preferenze</p>
</div>

<div class="settings-grid">

    <!-- PROFILO -->
    <div class="card profile-card">
        <div class="avatar-big"> <?= strtoupper(substr($user['username'], 0, 10)) ?></div>

        <h3><?= htmlspecialchars($user['username'] ?? '') ?></h3>
        <p><?= htmlspecialchars($user['email'] ?? '') ?></p>

        <div class="badge"><?= htmlspecialchars($user['role'] ?? 'utente') ?></div>
    </div>

    <!-- INFO ACCOUNT -->
    <div class="card">
        <h3>Informazioni Account</h3>

        <div class="field">
            <label>Username</label>
            <div><?= htmlspecialchars($user['username'] ?? '') ?></div>
        </div>

        <div class="field">
            <label>Email</label>
            <div><?= htmlspecialchars($user['email'] ?? '') ?></div>
        </div>

        <div class="field">
            <label>Ruolo</label>
            <div><?= htmlspecialchars($user['role'] ?? '') ?></div>
        </div>

        <div class="field">
            <label>ID Sessione</label>
            <div><?= session_id() ?></div>
        </div>
    </div>

    <!-- SICUREZZA -->
    <div class="card">
        <h3>🔐 Sicurezza</h3>

        <div class="field">
            <label>Password</label>
            <div>••••••••</div>
        </div>

        <button class="btn primary">Cambia Password</button>

        <div style="margin-top:15px" class="field">
            <label>Autenticazione 2FA</label>
            <div>Disabilitata</div>
        </div>

        <button class="btn">Attiva 2FA</button>
    </div>

    <!-- PREFERENZE -->
    <div class="card">
        <h3>⚙️ Preferenze</h3>

        <div class="field">
            <label>Tema</label>
            <div>Chiaro</div>
        </div>

        <div class="field">
            <label>Lingua</label>
            <div>Italiano</div>
        </div>

        <div class="field">
            <label>Time zone</label>
            <div>Europe/Rome</div>
        </div>
    </div>

    <!-- NOTIFICHE -->
    <div class="card">
        <h3>🔔 Notifiche</h3>

        <div class="field">
            <label>Email notifiche</label>
            <div>Attive</div>
        </div>

        <div class="field">
            <label>Push</label>
            <div>Disattivate</div>
        </div>

        <div class="field">
            <label>Marketing</label>
            <div>Disattivato</div>
        </div>
    </div>

    <!-- SESSIONE -->
    <div class="card">
        <h3>🖥️ Sessione attiva</h3>

        <div class="field">
            <label>Browser</label>
            <div><?= $_SERVER['HTTP_USER_AGENT'] ?></div>
        </div>

        <div class="field">
            <label>IP</label>
            <div><?= $_SERVER['REMOTE_ADDR'] ?></div>
        </div>

        <button class="btn">Logout da tutti i dispositivi</button>
    </div>

    <!-- AREA PERICOLOSA -->
    <div class="card" style="border:1px solid #ef4444;">
        <h3 style="color:#ef4444;">⚠️ Area pericolosa</h3>

        <p>Questa azione è irreversibile</p>

        <button class="btn danger">Elimina account</button>
    </div>

</div>

</main>

</div>

<?php require "includes/footer.php"; ?>

</body>
</html>
</html>