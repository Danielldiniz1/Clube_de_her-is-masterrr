<?php
echo $this->layout("_theme");
?>

<h1>Gerenciador de Produtos</h1>

<!-- Formulário para Adicionar Novo Produto -->
<div class="panel-card">
    <h3>Adicionar Novo Produto</h3>
    <form id="add-product-form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="add-name">Nome do Produto:</label>
            <input type="text" id="add-name" name="name" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="add-club_id">ID do Clube:</label>
            <input type="number" id="add-club_id" name="club_id" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="add-price">Preço (R$):</label>
            <input type="number" id="add-price" name="price" step="0.01" required autocomplete="off">
        </div>
        <div class="form-group">
            <label for="add-stock">Estoque:</label>
            <input type="number" id="add-stock" name="stock" value="0" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="add-category_id">ID da Categoria:</label>
            <input type="number" id="add-category_id" name="category_id" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="add-fandom">Fandom:</label>
            <input type="text" id="add-fandom" name="fandom" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="add-rarity">Raridade:</label>
            <select id="add-rarity" name="rarity" autocomplete="off">
                <option value="common">Comum</option>
                <option value="rare">Raro</option>
                <option value="exclusive">Exclusivo</option>
            </select>
        </div>

        <div class="form-group">
            <label for="add-images">Imagens do Produto:</label>
            <input type="file" id="add-images" name="images[]" multiple accept="image/*">
            <small>Selecione múltiplas imagens (máximo 5). A primeira será definida como principal.</small>
        </div>
        <div class="form-group">
            <label for="add-weight_grams">Peso (gramas):</label>
            <input type="number" id="add-weight_grams" name="weight_grams" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="add-dimensions_cm">Dimensões (cm):</label>
            <input type="text" id="add-dimensions_cm" name="dimensions_cm" placeholder="Ex: 30x20x10" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="add-description">Descrição:</label>
            <textarea id="add-description" name="description" autocomplete="off"></textarea>
        </div>
        <div class="form-group form-group-checkbox">
            <input type="checkbox" id="add-is_physical" name="is_physical" value="1" checked>
            <label for="add-is_physical">Produto Físico</label>
        </div>
        <div class="form-group form-group-checkbox">
            <input type="checkbox" id="add-subscription_only" name="subscription_only" value="1">
            <label for="add-subscription_only">Apenas para Assinantes</label>
        </div>
        <div class="form-group form-group-checkbox">
            <input type="checkbox" id="add-is_active" name="is_active" value="1" checked>
            <label for="add-is_active">Produto Ativo</label>
        </div>
        <button type="submit" class="btn">Adicionar Produto</button>
    </form>
</div>

<div class="panel-card">
    <h3>Produtos Cadastrados</h3>
    <table class="data-table" id="products-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>ID Clube</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="products-table-body">
            <!-- Linhas populadas via JS -->
        </tbody>
    </table>
</div>

<!-- Modal para Editar Produto -->
<div id="edit-product-modal" class="admin-modal">
    <div class="modal-content panel-card">
        <span class="close-modal-btn">&times;</span>
        <h3 id="edit-form-title">Editar Produto</h3>
        <form id="edit-product-form" enctype="multipart/form-data">
            <!-- Conteúdo do formulário de edição será populado via JS -->
        </form>
    </div>
</div>

<?php $this->start("scripts"); ?>
<script type="module" src="<?= url("assets/js/adm/manage-products.js"); ?>"></script>
<?php $this->end(); ?>