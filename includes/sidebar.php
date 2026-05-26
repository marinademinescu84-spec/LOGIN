<aside class="sidebar">

    <a href="dashboard.php"
       class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">
       🏠 Dashboard
    </a>

    <a href="profilo.php"
       class="<?= ($activePage ?? '') === 'profilo' ? 'active' : '' ?>">
       👤 Profilo
    </a>

    <a href="impostazioni.php"
       class="<?= ($activePage ?? '') === 'impostazioni' ? 'active' : '' ?>">
       ⚙️ Impostazioni
    </a>

    <a href="file.php"
       class="<?= ($activePage ?? '') === 'file' ? 'active' : '' ?>">
       📁 File
    </a>
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator'): ?>

    <a href="mini-admin.php"
       class="<?= ($activePage ?? '') === 'admin' ? 'active' : '' ?>">
       🛡️ Gestione DB
    </a>

    <a href="admin-audio.php"
       class="<?= ($activePage ?? '') === 'admin-audio' ? 'active' : '' ?>">
       🎧 Audio Giuria
    </a>

<?php endif; ?>
    <a class="logout" href="logout.php">
        🚪 Logout
    </a>

</aside>