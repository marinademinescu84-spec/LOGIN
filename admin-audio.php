<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'administrator') {
    header("Location: login_page.php");
    exit;
}

require "db.php";

$activePage = 'admin-audio';
/*
|---------------------------------------------------------
| PRENDE TUTTI GLI AUDIO CON UTENTE
|---------------------------------------------------------
*/
$sql = "
SELECT 
    files.id,
    files.nome_file,
    files.tipo,
    files.dimensione,
    files.percorso,
    files.data_upload,
    utenti.nome,
    utenti.cognome,
    utenti.email
FROM files
JOIN utenti ON files.utente_id = utenti.id
ORDER BY files.data_upload DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Admin Audio Giuria</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>

<?php require "includes/header.php"; ?>

<div class="main-wrapper">

    <?php require "includes/sidebar.php"; ?>

    <main class="content">

        <div class="page-header">
            <h2>🎧 Gestione Audio Giuria</h2>
            <p>Ascolta tutti i file inviati dagli utenti</p>
        </div>

        <div class="card">

            <div class="table-responsive">

                <table class="admin-table">

                    <thead>
                        <tr>
                            <th>Utente</th>
                            <th>Email</th>
                            <th>File</th>
                            <th>Data</th>
                            <th>Dimensione</th>
                            <th>Ascolta</th>
                            <th>Scarica</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($result && $result->num_rows > 0): ?>

                        <?php while($row = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($row['nome'].' '.$row['cognome']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['email']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nome_file']) ?>
                                </td>

                                <td>
                                    <?= date("d/m/Y H:i", strtotime($row['data_upload'])) ?>
                                </td>

                                <td>
                                    <?= round($row['dimensione']/1024/1024, 2) ?> MB
                                </td>

                                <td>
                                    <audio controls style="width:180px;">
                                        <source src="<?= htmlspecialchars($row['percorso']) ?>" type="audio/mpeg">
                                    </audio>
                                </td>

                                <td>
                                    <a class="btn-sm btn-success"
                                       href="<?= htmlspecialchars($row['percorso']) ?>"
                                       download>
                                        Scarica
                                    </a>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" style="text-align:center; padding:20px;">
                                Nessun audio caricato
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

<?php require "includes/footer.php"; ?>

</body>
</html>