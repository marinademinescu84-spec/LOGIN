<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrator') {
    header("Location: login_page.php");
    exit("Accesso negato");
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

require "db.php";
$activePage = 'admin';

/* AZIONI (NON MODIFICATO) */
if (isset($_GET['azione']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if ($_GET['azione'] === 'attiva') {
        $stmt = $conn->prepare("UPDATE utenti SET is_active = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    if ($_GET['azione'] === 'elimina') {
        $stmt = $conn->prepare("DELETE FROM utenti WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: mini-admin.php");
    exit;
}

$risultato = $conn->query("SELECT id, nome, cognome, email, role, is_active FROM utenti ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>

<?php if (file_exists("includes/header.php")) require "includes/header.php"; ?>

<div class="main-wrapper">

    <?php if (file_exists("includes/sidebar.php")) { ?>
        <div class="sidebar-wrapper">
            <?php require "includes/sidebar.php"; ?>
        </div>
    <?php } ?>

    <main class="content">

        <div class="page-header">
            <h2>🛡️ Pannello Amministratore</h2>
            <p>Gestione utenti registrati al sistema</p>
        </div>

        <div class="card">

            <h3 class="admin-table-title">Lista utenti database</h3>

            <div class="table-responsive">
                <table class="admin-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Ruolo</th>
                            <th>Stato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($risultato && $risultato->num_rows > 0): ?>
                        <?php while($u = $risultato->fetch_assoc()): ?>
                            <tr>

                                <td>#<?= (int)$u['id'] ?></td>

                                <td>
                                    <?= htmlspecialchars(($u['nome'] ?? '') . ' ' . ($u['cognome'] ?? '')) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($u['email'] ?? '') ?>
                                </td>

                                <td>
                                    <span class="badge <?= ($u['role'] === 'administrator') ? 'blue' : 'green' ?>">
                                        <?= htmlspecialchars($u['role'] ?? '') ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if (($u['is_active'] ?? 0) == 1): ?>
                                        <span class="status-badge status-active">ATTIVO</span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending">NON ATTIVO</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="admin-actions-flex">

                                        <?php if (($u['is_active'] ?? 0) == 0): ?>
                                            <a class="btn-sm btn-success"
                                               href="mini-admin.php?azione=attiva&id=<?= (int)$u['id'] ?>">
                                                Attiva
                                            </a>
                                        <?php endif; ?>

                                        <a class="btn-sm btn-danger"
                                           href="mini-admin.php?azione=elimina&id=<?= (int)$u['id'] ?>"
                                           onclick="return confirm('Confermi eliminazione?');">
                                            Elimina
                                        </a>

                                    </div>
                                </td>

                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="admin-table-empty">
                                Nessun utente trovato
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>
            </div>

        </div>

    </main>
</div>

<footer class="footer">
    © <?= date('Y') ?> AIDO APP
</footer>

</body>
</html>