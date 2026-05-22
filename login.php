<?php
session_start();
require "db.php";

// 🔧 ATTIVA / DISATTIVA DEBUG
$debug = false;

function debug($msg) {
    global $debug;
    if ($debug) {
        echo "[DEBUG] " . $msg . "<br>";
    }
}

// INPUT
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = $_POST['password'] ?? '';
$email = $_POST['email'] ?? '';

//debug("Username ricevuto: " . $username);

if ($username === '' || $password === '' ) {
    debug("Username o password vuoti");
    echo "NO login";
    exit;
}

// QUERY
$sql = "SELECT username, email, role, password FROM utenti WHERE username = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    debug("Errore prepare: " . $conn->error);
    echo "NO result";
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if (!$result) {
    debug("Errore get_result");
    echo "NO getresult";
    exit;
}

$user = $result->fetch_assoc();

//debug("Utente trovato: " . ($user ? "SI" : "NO"));

if ($user) {
    //debug("Password hash DB: " . $user['password']);

    if (password_verify($password, $user['password'])) {

        //debug("Password corretta");

        session_regenerate_id(true);
        $_SESSION['username'] = $user['username'];

        //debug("Sessione impostata");

        echo "OK";
    } else {
       
        echo "NO password";
    }
} else {
    //debug("Utente non trovato");
    echo "NO Utente";
}
?>