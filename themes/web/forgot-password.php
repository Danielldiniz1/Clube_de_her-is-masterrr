<?php $this->layout("_theme"); ?>

<section class="auth-section">
    <div class="auth-container">
        <div class="auth-header">
            <h1>Recuperar Senha</h1>
            <p>Digite seu e-mail para receber as instruções.</p>
        </div>
        <div id="toast-container"></div>
        <form id="forgot-password-form" class="auth-form" action="<?= url('esqueci-a-senha') ?>" method="post">
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="btn-submit">Enviar Link de Recuperação</button>
        </form>
    </div>
</section>