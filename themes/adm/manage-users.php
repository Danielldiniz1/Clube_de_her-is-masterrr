<?php
echo $this->layout("_theme");
?>

<h1>Gerenciador de Usuários</h1>

<!-- Formulário para Adicionar Novo Usuário -->
<div class="panel-card">
    <h3>Adicionar Novo Usuário</h3>
    <form id="add-user-form">
        <div class="form-group">
            <label for="add-name">Nome:</label>
            <input type="text" id="add-name" name="name" required autocomplete="name">
        </div>
        <div class="form-group">
            <label for="add-email">Email:</label>
            <input type="email" id="add-email" name="email" required autocomplete="email">
        </div>
        <div class="form-group">
            <label for="add-password">Senha:</label>
            <input type="password" id="add-password" name="password" required autocomplete="new-password">
        </div>
        <div class="form-group">
            <label for="add-confirm-password">Confirmar Senha:</label>
            <input type="password" id="add-confirm-password" name="confirm_password" required autocomplete="new-password">
        </div>
        <div class="form-group">
            <label for="add-photo">Foto (URL):</label>
            <input type="text" id="add-photo" name="photo" autocomplete="photo">
        </div>
        <div class="form-group">
            <label for="add-idType">Tipo de Usuário:</label>
            <select id="add-idType" name="idType">
                <option value="2">Cliente</option>
                <option value="1">Vendedor</option>
            </select>
        </div>
        <button type="submit" class="btn">Adicionar Usuário</button>
    </form>
</div>

<div class="panel-card">
    <h3>Usuários Cadastrados</h3>
    <table class="data-table" id="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="users-table-body">
            <!-- Linhas populadas via JS -->
        </tbody>
    </table>
</div>

<!-- Modal para Editar Usuário -->
<div id="edit-user-modal" class="admin-modal">
    <div class="modal-content panel-card">
        <span class="close-modal-btn">&times;</span>
        <h3 id="edit-form-title">Editar Usuário</h3>
        <form id="edit-user-form">
            <input type="hidden" id="edit-user_id" name="id">
            <div class="form-group">
                <label for="edit-name">Nome:</label>
                <input type="text" id="edit-name" name="name" required autocomplete="name">
            </div>
            <div class="form-group">
                <label for="edit-email">Email:</label>
                <input type="email" id="edit-email" name="email" required autocomplete="email">
            </div>
            <div class="form-group">
                <label for="edit-password">Nova Senha (deixe em branco para não alterar):</label>
                <input type="password" id="edit-password" name="password" autocomplete="new-password">
            </div>
            <div class="form-group">
                <label for="edit-confirm-password">Confirmar Nova Senha:</label>
                <input type="password" id="edit-confirm-password" name="confirm_password" autocomplete="new-password">
            </div>
            <div class="form-group">
                <label for="edit-photo">Foto (URL):</label>
                <input type="text" id="edit-photo" name="photo" autocomplete="photo">
            </div>
            <div class="form-group">
                <label for="edit-idType">Tipo de Usuário:</label>
                <select id="edit-idType" name="idType">
                    <option value="2">Cliente</option>
                    <option value="1">Vendedor</option>
                </select>
            </div>
            <button type="submit" class="btn">Salvar Alterações</button>
        </form>
    </div>
</div>

<?php $this->start("scripts"); ?>
<script type="module" src="<?= url("assets/js/adm/manage-users.js"); ?>"></script>
<?php $this->end(); ?>