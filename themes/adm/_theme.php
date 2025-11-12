<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>..:: Área Administrativa ::..</title>
    <link rel="stylesheet" href="<?= url("assets/css/adm/styles.css"); ?>">
    <link rel="stylesheet" href="<?= url("assets/css/adm/product-images.css"); ?>"> 
    <script>window.__APP_BASE = "<?= url(); ?>";</script>
    </head>
<body class="admin-area">
    <aside class="admin-sidebar">
        <h2>Área Administrativa</h2>
        <nav>
            <div class="admin-menu-toggle">☰</div>
            <ul class="admin-nav-menu">
                <li><a href="<?= url('admin/manusuarios'); ?>">Gerenciar Usuários</a></li>
                <li><a href="<?= url('admin/manclubes'); ?>">Gerenciar Clubs</a></li>
                <li><a href="<?= url('admin/manprodutos'); ?>">Gerenciar produtos</a></li>
            </ul>
        </nav>
    </aside>
    <div class="admin-content">
        <div class="admin-main-content"> <?php
                echo $this->section("content");
            ?>
        </div>
        <footer class="admin-footer">
            © 2025 Clube de Heróis - Painel Administrativo.
        </footer>
    </div>

    <script>
        // Script para toggle do menu em mobile para a área ADM
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.admin-menu-toggle');
            const navMenu = document.querySelector('.admin-nav-menu');

            if (menuToggle && navMenu) {
                menuToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('active');
                });
            }
        });
    </script>

    <?php if ($this->section("scripts")): ?>
        <?= $this->section("scripts") ?>
    <?php endif; ?>
</body>
</html>