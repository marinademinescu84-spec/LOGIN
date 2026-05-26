<?php
session_start();
require "db.php";

// 🔧 ATTIVA / DISATTIVA DEBUG
$debug = false;

function debug($msg) {
    global $debug;
    if ($debug) {
        error_log("[DEBUG] " . $msg); 
    }
}

// INPUT (Riceve l'email dal form di login_page.php)
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = $_POST['password'] ?? '';

debug("Tentativo di login per email: " . $email);

if ($email === '' || $password === '') {
    debug("Email o password vuote");
    echo "NO login";
    exit;
}

// 🌟 CORREZIONE 1: Aggiunto 'role' nella SELECT per sapere se l'utente è Admin o User
$sql = "SELECT id, nome, cognome, password, role, is_active FROM utenti WHERE email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    debug("Errore prepare: " . $conn->error);
    echo "NO result";
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    debug("Errore get_result");
    echo "NO getresult";
    exit;
}

$user = $result->fetch_assoc();

if ($user) {
    debug("Utente trovato nel DB");

    // 1. CONTROLLO ATTIVAZIONE
    if ((int)$user['is_active'] === 0) {
        debug("Account non attivo");
        echo "NON_ATTIVO";
        exit;
    }

    // 2. VERIFICA PASSWORD
    if (password_verify($password, $user['password'])) {
        debug("Password corretta");

        // Rigenera la sessione per sicurezza e salva i dati utili
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['cognome'] = $user['cognome'];
        
        // 🌟 CORREZIONE 2: Salva il ruolo in sessione (User o Administrator)
        $_SESSION['role'] = $user['role']; 

        // 3. AGGIORNA LAST_LOGIN
        $updateSql = "UPDATE utenti SET last_login = NOW() WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        if ($updateStmt) {
            $updateStmt->bind_param("i", $user['id']);
            $updateStmt->execute();
            debug("Last login aggiornato");
        }

        echo "OK";
    } else {
        debug("Password errata");
        echo "NO password";
    }
} else {
    debug("Nessun utente trovato con questa email");
    echo "NO utente";
}
?>