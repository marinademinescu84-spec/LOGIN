<?php
session_start();

// svuota tutte le variabili di sessione
$_SESSION = [];

// distrugge la sessione
session_destroy();

// (opzionale ma consigliato) elimina il cookie di sessione
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// redirect al login
header("Location: index.php");
exit;

?>