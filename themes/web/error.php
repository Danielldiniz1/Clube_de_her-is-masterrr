<?php $this->layout("_theme"); ?>

<div class="container">
    <div class="error-container" style="text-align: center; padding: 50px;">
        <h1 class="comic-font">Oops! Erro <?= $error; ?></h1>
        <p>Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.</p>
        <a href="<?= url(); ?>" class="btn-login" style="margin-top: 20px; display: inline-block;">Voltar para a Home</a>
    </div>
</div>