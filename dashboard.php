<?php require "includes/auth.php"; 
$activePage = 'dashboard'; 

?>



<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>

<?php require "includes/header.php"; ?>

<div class="container">

    <?php require "includes/sidebar.php"; ?>   
  
    <!-- CONTENT -->
    <main class="content">
        <h1>Benvenuto 👋 <?= htmlspecialchars($user['username'] ?? '') ?></h1>
        <p>Questa è la tua area riservata.</p>

        <div class="cards">

    <div class="card">
        <div class="icon">💻</div>
        <h3>Informatica</h3>
        <p>Sviluppo software, sistemi e infrastrutture IT moderne.</p>
        <b>Progetti attivi: 12</b>
    </div>

    <div class="card">
        <div class="icon">⚙️</div>
        <h3>Backend</h3>
        <p>API, database e logica server performante e scalabile.</p>
        <b>Servizi: 8</b>
    </div>

    <div class="card">
        <div class="icon">📊</div>
        <h3>Consulenza</h3>
        <p>Supporto tecnico e strategico per aziende e startup.</p>
        <b>Clienti: 24</b>
    </div>

</div>
    </main>

</div>

<?php require "includes/footer.php"; ?>

</body>
</html>