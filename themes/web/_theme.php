<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clube de heróis</title>
    <meta name="description" content="Plataforma white-label de clubes de assinatura geek para criadores de conteúdo e lojas" />
    <meta name="author" content="Clube de Heróis" />
    <meta property="og:title" content="Clube de Heróis - Plataforma de Assinatura Geek" />
    <meta property="og:description" content="Plataforma white-label de clubes de assinatura geek para criadores de conteúdo e lojas" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="https://lovable.dev/opengraph-image-p98pqg.png" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@lovable_dev" />
    <meta name="twitter:image" content="https://lovable.dev/opengraph-image-p98pqg.png" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bangers&family=Roboto:wght@400;500;700&display=swap">
    <body>
    <div id="root"></div>
    <script src="https://cdn.gpteng.co/gptengineer.js" type="module"></script>
  </body>
</head>
    <link rel="stylesheet" href="<?= url("assets/css/web/main.css"); ?>">
    <link rel="stylesheet" href="<?= url("assets/css/web/components.css"); ?>">
    <script src="<?= url("assets/js/web/scripts-toasts.js"); ?>" defer></script>

<?php if ($this->section("specific-script")): ?>
    <?= $this->section("specific-script"); ?>
<?php endif; ?>
</head>
<body>
<header class="public-header"> <div class="container">
        <nav>
            <a href="<?= url(); ?>" class="logo comic-font">CLUBE DE HERÓIS</a>
            <ul class="nav-menu">
                <li><a href="<?= url(); ?>">Início</a></li>
                <li><a href="<?= url('sobre'); ?>">Sobre</a></li>
                <li><a href="<?= url('contato'); ?>">Contato</a></li>
                <li><a href="<?= url('faqs'); ?>">Faq</a></li>
                <li><a href="<?= url('login'); ?>" class="btn-login">Login</a></li>
            </ul>
            <div class="menu-toggle">☰</div>
        </nav>
    </div>
</header>
    <?php
        echo $this->section("content");
    ?>
<footer>
    <div class="container">
      <a href="#" class="footer-logo comic-font">CLUBE DE HERÓIS</a>
      <ul class="footer-links">
        <li><a href="#">Início</a></li>
        <li><a href="#plans">Planos</a></li>
        <li><a href="<?= url('sobre'); ?>">Sobre</a></li>
        <li><a href="<?= url('contato'); ?>">Contato</a></li>
      </ul>
      <div class="copyright">© 2025 Clube de Heróis. Todos os direitos reservados.</div>
    </div>
  </footer>
</body>
</html>