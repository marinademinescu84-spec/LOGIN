<header class="navbar">
    <div class="logo">AIDOApp</div>

    <div class="user">
        👤 <?= htmlspecialchars($_SESSION['nome'] ?? 'Utente') ?>
    </div>

    <div class="nav-admin-side">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'administrator'): ?>
            <a href="mini-admin.php"
               class="nav-link-admin a-db <?= ($activePage ?? '') === 'admin' ? 'active' : '' ?>">
                🛡️ Gestione DB
            </a>
        <?php endif; ?>
    </div>
</header>