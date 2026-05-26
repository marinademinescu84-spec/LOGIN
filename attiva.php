<?php
require "db.php";

$token = $_GET['token'] ?? '';

if ($token === '') {
    die("Token mancante o non valido.");
}

$sql = "SELECT id FROM utenti WHERE activation_token = ? AND is_active = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attivazione Account - AIDO</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>

<div class="login-page">

    <div class="form">

        <?php if ($user): ?>

            <?php
            $updateSql = "UPDATE utenti SET is_active = 1, activation_token = NULL WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("i", $user['id']);
            $success = $updateStmt->execute();
            ?>

            <?php if ($success): ?>
                <h2>🎉 Account attivato</h2>

                <p>
                    Il tuo account è stato attivato con successo.
                </p>

                <a href="login_page.php">
                    <button>Vai al Login</button>
                </a>

            <?php else: ?>
                <h2>Errore</h2>
                <p>Errore durante l’attivazione dell’account.</p>
            <?php endif; ?>

        <?php else: ?>

            <h2>❌ Link non valido</h2>

            <p>
                Il link è scaduto oppure l’account è già stato attivato.
            </p>

            <a href="login_page.php">
                <button>Torna al Login</button>
            </a>

        <?php endif; ?>

    </div>

</div>

</body>
</html>