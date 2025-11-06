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
    <link rel="stylesheet" href="<?= url("themes/app/style.css"); ?>">
    <?php if ($this->section("specific-script")): ?>
        <?= $this->section("specific-script"); ?>
    <?php endif; ?>
    
</head>
<body>
<header class="public-header"> <div class="container">
        <nav>
            <a href="<?= url(); ?>" class="logo comic-font">CLUBE DE HERÓIS</a>
            <ul class="nav-menu">
                <li><a href="<?= url('app/minhascompras'); ?>">Minhas compras</a></li>
                <li><a href="<?= url('app/produtos'); ?>">Produtos</a></li>
                <li><a href="<?= url('app/carrinho'); ?>">Meu carrinho</a></li>
                <li><a href="<?= url('app/meuclube'); ?>">Meu clube</a></li>
                <li><a href="<?= url('app/listadedesejos'); ?>">Lista de desejos</a></li>
                <li><a href="<?= url('app/perfil'); ?>">Perfil</a></li>     
                <li><a href="" id="change">Trocar senha</a></li>
            </ul>
        </nav>
    </div>

</header>
    <?php
    
echo '<main class="app-container">';
echo $this->section("content");
echo '</main>';
    ?>
        <div class="modal" id="changePasswordModal">
     <link rel="stylesheet" href="<?= url("assets/css/app/switch.css"); ?>">

    <div class="modal-content">
      <h2>Alterar Senha</h2>
      <form>
        <label for="currentPassword">Senha Atual</label>
        <input type="password" id="currentPassword" name="currentPassword" required autocomplete="current-password">

        <label for="newPassword">Nova Senha</label>
        <input type="password" id="newPassword" name="newPassword" required autocomplete="new-password">

        <label for="confirmPassword">Confirmar Nova Senha</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required autocomplete="new-password">

        <div class="buttons">
          <button type="submit" class="save">Salvar</button>
          <button type="button" class="cancel" id="cancel">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

<!-- Modal de Edição de Perfil -->
<div class="modal" id="editProfileModal">
    <div class="modal-content">
        <h2>Editar Perfil</h2>
        <form id="editProfileForm">
            <div class="form-group">
                <label for="name">Nome Completo</label>
                <input type="text" id="name" name="name" required autocomplete="name">
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>
            <div class="form-group">
                <label for="idType">Tipo de Conta</label>
                <select id="idType" name="idType">
                    <option value="2">Cliente</option>
                    <option value="1">Vendedor</option>
                </select>
            </div>
            <div class="buttons">
                <button type="submit" class="save">Salvar Alterações</button>
                <button type="button" class="cancel" id="cancelEditProfile">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<footer>
    <div class="container">
      <p class="footer-logo comic-font">CLUBE DE HERÓIS</p>
      <ul class="footer-links">
        <li><a href="#">Início</a></li>
        <li><a href="#plans">Planos</a></li>
        <li><a href="<?= url('sobre'); ?>">Sobre</a></li>
        <li><a href="<?= url('contato'); ?>">Contato</a></li>
      </ul>
      <div class="copyright">© 2025 Clube de Heróis. Todos os direitos reservados.</div>
    </div>
  </footer>

  <script src="<?= url("assets/js/app/scripts-change-password.js"); ?>" async></script>

  <?php if ($this->section("post-scripts")): ?>
    <?= $this->section("post-scripts"); ?>
  <?php endif; ?>

  <script>
    // Sincroniza token do localStorage com cookie, garantindo acesso nas rotas /app
    (function(){
      function getCookie(name){
        const m = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return m ? decodeURIComponent(m[2]) : null;
      }
      var c = getCookie('token');
      var t = localStorage.getItem('token');
      if (t && !c) {
        var expires = new Date(Date.now() + 90 * 60 * 1000).toUTCString();
        document.cookie = 'token=' + t + '; expires=' + expires + '; path=/';
      }
    })();
  </script>

</body>
</html>