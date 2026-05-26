<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit;
}

require "db.php";

$activePage = 'file';
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM files WHERE utente_id = ? ORDER BY data_upload DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$fileCaricati = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invio Audio - Concorso AIDO</title>

    <link rel="stylesheet" href="index.css">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>

<body>

<?php
if (file_exists("includes/header.php")) {
    require "includes/header.php";
}
?>

<div class="main-wrapper">

    <div class="sidebar-wrapper">
        <?php
        if (file_exists("includes/sidebar.php")) {
            require "includes/sidebar.php";
        }
        ?>
    </div>

    <main class="content">

        <div class="page-header">
            <h2>🎵 Partecipazione e Invio Audio</h2>

            <p>
                Invia il tuo file audio ufficiale per completare
                la partecipazione al concorso AIDO.
            </p>
        </div>

        <div class="info-box-aido">

            <h4>Regolamento Ufficiale:</h4>

            <p>
                Il file audio deve esprimere i valori legati
                alla donazione degli organi.

                Formato supportato:
                <b>MP3</b>

                (Dimensione Massima: 50MB).

                È consentito
                <b>un solo file</b>
                per iscritto.
            </p>
        </div>

        <?php if ($fileCaricati === 0): ?>

            <div class="upload-dropzone">

                <form action="upload.php" method="POST" enctype="multipart/form-data">

                    <span class="material-symbols-outlined icon-video">
                        audio_file
                    </span>

                    <p>
                        Seleziona il file audio del tuo progetto
                    </p>

                    <input
                        type="file"
                        name="file"
                        accept=".mp3,audio/mpeg"
                        required
                    >

                    <button type="submit" class="btn aido-primary">

                        <span class="material-symbols-outlined">
                            upload
                        </span>

                        Trasmetti Audio alla Giuria
                    </button>

                </form>
            </div>

        <?php else: ?>

            <div class="card alert-success-aido">

                <p>
                    <span class="material-symbols-outlined icon-check">
                        check_circle
                    </span>

                    Ottimo lavoro!
                    Il tuo file audio è stato inviato correttamente.
                </p>

            </div>

        <?php endif; ?>

        <div class="card table-card">

            <div class="table-header">
                <h3>Stato della tua Candidatura</h3>
            </div>

            <div class="table-responsive">

                <table class="file-table">

                    <thead>
                        <tr>
                            <th>Nome File</th>
                            <th>Formato</th>
                            <th>Data Upload</th>
                            <th>Dimensione</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($fileCaricati === 0): ?>

                            <tr>
                                <td colspan="5" class="table-empty-state">

                                    Nessun file registrato.
                                    Carica un MP3 tramite il box superiore.

                                </td>
                            </tr>

                        <?php endif; ?>

                        <?php while ($file = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <div class="file-name">

                                        <span class="material-symbols-outlined">
                                            audio_file
                                        </span>

                                        <?= htmlspecialchars($file['nome_file'] ?? '') ?>

                                    </div>
                                </td>

                                <td>
                                    <span class="tag green-tag">
                                        <?= strtoupper(htmlspecialchars($file['tipo'] ?? '')) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= date("d/m/Y H:i", strtotime($file['data_upload'] ?? 'now')) ?>
                                </td>

                                <td>
                                    <?= round(($file['dimensione'] ?? 0) / (1024 * 1024), 2) ?> MB
                                </td>

                                <td>

                                    <a
                                        href="delete.php?id=<?= $file['id'] ?>"
                                        onclick="return confirm('Vuoi davvero eliminare il file audio caricato?')"
                                        style="text-decoration: none;"
                                    >

                                        <button class="icon-btn">

                                            <span class="material-symbols-outlined">
                                                delete
                                            </span>

                                            Elimina
                                        </button>

                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

<?php
if (file_exists("includes/footer.php")) {
    require "includes/footer.php";
}
?>

</body>
</html>