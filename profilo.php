<?php
require "includes/auth.php";
require "db.php";

$activePage = 'profilo';

$username = $_SESSION['username'] ?? '';

$sql = "SELECT username, email, role FROM utenti WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();


if (!$user) {
    die("Utente non trovato o sessione non valida");
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Profilo</title>
<link rel="stylesheet" href="index.css">
</head>

<body>

<?php require "includes/header.php"; ?>

<div class="container">

<?php require "includes/sidebar.php"; ?>

<main class="content">

<div class="page-header">
  <h2>👤 Profilo Utente</h2>
</div>

<div class="profile-grid">

  <div class="card profile-card">
    <div class="avatar-big">
      <?= strtoupper(substr($user['username'], 0, 10)) ?>
    </div>

    <h3><?= htmlspecialchars($user['username'] ?? '') ?></h3>
    <div class="badge">Admin</div>
  </div>

  <div class="card">
    <div class="field">
      <label>Email</label>
      <div><?= htmlspecialchars($user['email'] ?? '') ?></div>
    </div>

    <div class="field">
      <label>Ruolo</label>
      <div><?= htmlspecialchars($user['role'] ?? '') ?></div>
    </div>
  </div>

</div>

</main>

</div>

<?php require "includes/footer.php"; ?>

</body>
</html>