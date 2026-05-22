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

    <a class="logout" href="logout.php">
        🚪 Logout
    </a>

</aside>