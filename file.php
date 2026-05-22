<?php
require "includes/auth.php";
require "db.php";

$stmt = $conn->prepare("SELECT * FROM files ORDER BY data_upload DESC");
$stmt->execute();
$result = $stmt->get_result();

$activePage = 'file';

$username = $_SESSION['username'] ?? '';

$docs = 0;
$images = 0;
$reports = 0;
$videos = 0;

$stmt2 = $conn->prepare("SELECT tipo FROM files");
$stmt2->execute();
$res2 = $stmt2->get_result();

while ($row = $res2->fetch_assoc()) {
    $type = strtolower($row['tipo']);

    if (in_array($type, ['pdf', 'doc', 'docx', 'txt'])) {
        $docs++;
    }
    elseif (in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
        $images++;
    }
    elseif (in_array($type, ['xls', 'xlsx', 'csv'])) {
        $reports++;
    }
    elseif (in_array($type, ['mp4', 'avi', 'mov'])) {
        $videos++;
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
<meta charset="UTF-8">
<title>Gestione File</title>

<link rel="stylesheet" href="index.css">

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>

<body>

<?php require "includes/header.php"; ?>

<div class="container">

<?php require "includes/sidebar.php"; ?>

<main class="content">

<div class="page-header">
    <h2>📁 Gestione File</h2>
    <p>Gestisci documenti, immagini, video e report.</p>
</div>

<!-- Bottone upload -->
<!-- Upload file -->
<div class="top-actions">

    <form action="upload.php" method="POST" enctype="multipart/form-data">

        <input type="file" name="file" required>

        <button type="submit" class="btn primary">
            <span class="material-symbols-outlined">upload</span>
            Carica File
        </button>

    </form>

</div>

<!-- Categorie -->
<div class="cards">

    <div class="card file-category">
        <div class="icon blue">
            <span class="material-symbols-outlined">description</span>
        </div>

        <h3>Documenti</h3>
        <p><?= $docs ?> file</p>
    </div>

    <div class="card file-category">
        <div class="icon green">
            <span class="material-symbols-outlined">image</span>
        </div>

        <h3>Foto</h3>
        <p><?= $images ?> file</p>
    </div>

    <div class="card file-category">
        <div class="icon orange">
            <span class="material-symbols-outlined">assessment</span>
        </div>

        <h3>Report</h3>
        <p><?= $reports ?> file</p>
    </div>

    <div class="card file-category">
        <div class="icon purple">
            <span class="material-symbols-outlined">movie</span>
        </div>

        <h3>Video</h3>
       <p><?= $videos ?> file</p>
    </div>

</div>

<!-- Tabella -->
<div class="card table-card">

    <div class="table-header">
        <h3>File Recenti</h3>
    </div>

    <div class="table-responsive">

        <table class="file-table">

            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Data</th>
                    <th>Dimensione</th>
                    <th>Azione</th>
                </tr>
            </thead>

            <tbody>

                
<?php while ($file = $result->fetch_assoc()): ?>

<tr>
    <td>
        <div class="file-name">
            <span class="material-symbols-outlined">
                description
            </span>

            <?= htmlspecialchars($file['nome_file'] ?? '') ?>
        </div>
    </td>

    <td>
        <span class="tag">
            <?= strtoupper(htmlspecialchars($file['tipo'] ?? '')) ?>
        </span>
    </td>

    <td>
        <?= date("d M Y", strtotime($file['data_upload'] ?? 'now')) ?>
    </td>

    <td>
        <?= htmlspecialchars($file['dimensione'] ?? '') ?>
    </td>

    <td>
        <a href="<?= htmlspecialchars($file['percorso'] ?? '#') ?>" download>
            <button class="icon-btn">
                <span class="material-symbols-outlined">download</span>
            </button>
        </a>

          <!-- DELETE -->
    <a href="delete.php?id=<?= $file['id'] ?>" onclick="return confirm('Sei sicuro di voler eliminare questo file?')">
        <button class="icon-btn delete-btn">
            <span class="material-symbols-outlined">delete</span>
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

<?php require "includes/footer.php"; ?>

</body>
</html>