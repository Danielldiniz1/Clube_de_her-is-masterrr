<?php $this->layout("_theme"); ?>

<section class="auth-section">
    <div class="auth-container">
        <div class="auth-header">
            <h1>Crie uma Nova Senha</h1>
        </div>
        <div id="toast-container"></div>
        <form id="reset-password-form" class="auth-form" action="<?= url('redefinir-senha') ?>" method="post">
            <input type="hidden" name="token" value="<?= $token; ?>">
            <div class="form-group">
                <label for="password">Nova Senha:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirm">Confirme a Nova Senha:</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>
            <button type="submit" class="btn-submit">Redefinir Senha</button>
        </form>
    </div>
</section>