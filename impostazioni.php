<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Controllo protezione accesso
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit;
}

require "db.php";
$activePage = 'impostazioni';
$userId = $_SESSION['user_id'];

$errorMsg = "";
$successMsg = "";

// ==========================================================================
// LOGICA DI AGGIORNAMENTO PASSWORD (FUNZIONANTE)
// ==========================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        $errorMsg = "Tutti i campi sono obbligatori.";
    } elseif ($newPassword !== $confirmPassword) {
        $errorMsg = "La nuova password e la conferma non coincidono.";
    } elseif (strlen($newPassword) < 6) {
        $errorMsg = "La nuova password deve contenere almeno 6 caratteri.";
    } else {
        // Recupera l'hash della password attuale dal database per verificarlo
        $stmtPass = $conn->prepare("SELECT password FROM utenti WHERE id = ?");
        $stmtPass->bind_param("i", $userId);
        $stmtPass->execute();
        $resPass = $stmtPass->get_result();
        $userPass = $resPass->fetch_assoc();

        if ($userPass && password_verify($oldPassword, $userPass['password'])) {
            // Genera il nuovo hash sicuro
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmtUpdate = $conn->prepare("UPDATE utenti SET password = ? WHERE id = ?");
            $stmtUpdate->bind_param("si", $newHash, $userId);
            
            if ($stmtUpdate->execute()) {
                $successMsg = "Password aggiornata con successo!";
            } else {
                $errorMsg = "Errore di sistema durante l'aggiornamento. Riprova.";
            }
            $stmtUpdate->close();
        } else {
            $errorMsg = "La password attuale inserita non è corretta.";
        }
        $stmtPass->close();
    }
}

// ==========================================================================
// RECUPERO DATI UTENTE ORIGINALE PER I BOX
// ==========================================================================
$stmt = $conn->prepare("SELECT nome, cognome, email, role FROM utenti WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    session_destroy();
    header("Location: login_page.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Impostazioni Account</title>
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
            <h2>⚙️ Impostazioni</h2>
            <p>Gestisci il tuo account di partecipazione e la sicurezza</p>
        </div>

        <div class="settings-grid">
            <div class="card profile-card">
                <div class="avatar-big"><?= strtoupper(substr($user['nome'] ?? 'U', 0, 1)) ?></div>
                <h3><?= htmlspecialchars(($user['nome'] ?? '') . ' ' . ($user['cognome'] ?? '')) ?></h3>
                <p class="email-subtext"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                <div class="badge"><?= htmlspecialchars($user['role'] ?? 'utente') ?></div>
            </div>

            <div class="card">
                <h3>Informazioni Account</h3>
                <div class="field mt-10">
                    <label>Email Accesso</label>
                    <div><?= htmlspecialchars($user['email'] ?? '') ?></div>
                </div>
                <div class="field">
                    <label>ID Sessione Concorso</label>
                    <div class="font-mono-small"><?= session_id() ?></div>
                </div>
            </div>

            <div class="card">
                <h3>🔐 Sicurezza</h3>
                
                <?php if (!empty($errorMsg)): ?>
                    <div class="alert-msg error"><?= $errorMsg ?></div>
                <?php endif; ?>
                <?php if (!empty($successMsg)): ?>
                    <div class="alert-msg success"><?= $successMsg ?></div>
                <?php endif; ?>
<form action="impostazioni.php" method="POST" class="mt-10">
    <input type="hidden" name="action" value="change_password">
    
    <div class="field">
        <label>Password Attuale</label> <br>
        <input type="password" name="old_password" class="field-input" required>
    </div> 

    <div class="field">
        <label>Nuova Password</label> <br>
        <input type="password" name="new_password" class="field-input" required>
    </div> 

    <div class="field">
        <label>Conferma Nuova Password</label> <br>
        <input type="password" name="confirm_password" class="field-input" required>
    </div> <br>

    <button type="submit" class="btn aido-primary w-full">Aggiorna Password</button>
</form>
     
        </div>
    </main>
</div>

<?php if (file_exists("includes/footer.php")) { require "includes/footer.php"; } ?>

</body>
</html>