<?php
echo $this->layout("_theme");
?>

<h1>Gerenciador de Clubes</h1>

<!-- Formulário para Adicionar Novo Clube -->
<div class="panel-card">
    <h3>Adicionar Novo Clube</h3>
    <form id="add-club-form">
        <div class="form-group">
            <label for="add-club_name">Nome do Clube:</label>
            <input type="text" id="add-club_name" name="club_name" required autocomplete="organization">
        </div>
        <div class="form-group">
            <label for="add-user_id">ID do Usuário (Criador):</label>
            <input type="number" id="add-user_id" name="user_id" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="add-description">Descrição:</label>
            <textarea id="add-description" name="description"></textarea>
        </div>
        <button type="submit" class="btn">Adicionar Clube</button>
    </form>
</div>

<div class="panel-card">
    <h3>Clubes Cadastrados</h3>
    <table class="data-table" id="clubs-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome do Clube</th>
                <th>ID do Criador</th>
                <th>Ativo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="clubs-table-body">
            <!-- Linhas populadas via JS -->
        </tbody>
    </table>
</div>

<!-- Modal para Editar Clube -->
<div id="edit-club-modal" class="admin-modal">
    <div class="modal-content panel-card">
        <span class="close-modal-btn">&times;</span>
        <h3 id="edit-form-title">Editar Clube</h3>
        <form id="edit-club-form">
            <!-- Conteúdo do formulário de edição será populado via JS -->
        </form>
    </div>
</div>

<?php $this->start("scripts"); ?>
<script type="module" src="<?= url("assets/js/adm/manage-clubs.js"); ?>"></script>
<?php $this->end(); ?>