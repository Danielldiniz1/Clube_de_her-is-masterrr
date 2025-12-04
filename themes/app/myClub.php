<?php
echo $this->layout("_theme");
?>
<section class="myclub-section" style="padding:2rem 0; min-height:70vh;">
  <div class="container" style="max-width:1000px; margin:0 auto;">
    <h1 class="section-title" style="text-align:center; margin-bottom:1.5rem;">Meu Clube</h1>
    <?php if (empty($user) || (int)($user->idType ?? 2) !== 1): ?>
      <p style="text-align:center; color:#a0a0a0;">Apenas vendedores podem acessar esta página.</p>
    <?php else: ?>
      <?php if (empty($club) || empty($club->id)): ?>
        <p style="text-align:center; color:#a0a0a0;">Nenhum clube associado à sua conta.</p>
        <div class="create-club-card" style="background:#1a0d0d; border:1px solid #333; border-radius:12px; padding:1rem; box-shadow:0 10px 25px rgba(0,0,0,0.3); max-width:640px; margin:12px auto;">
          <button id="toggle-create-club" class="btn" style="padding:0.6rem 1rem; border-radius:10px; background:#ff3333; color:#000; border:none; font-family:'Bangers', cursive; display:block; margin:0 auto 12px auto;">Criar meu clube</button>
          <form id="create-club-form" action="<?= url('app/meuclube/criar'); ?>" method="post" style="display:none;">
            <div class="form-group">
              <label>Nome do Clube</label>
              <input type="text" name="club_name" required>
            </div>
            <div class="form-group">
              <label>Descrição</label>
              <textarea name="description"></textarea>
            </div>
            <div style="text-align:right;">
              <button type="submit" class="btn" style="padding:0.6rem 1rem; border-radius:10px; background:#ffd700; color:#000; border:none; font-family:'Bangers', cursive;">Salvar clube</button>
            </div>
          </form>
        </div>
      <?php else: ?>
        <div class="club-info" style="margin-bottom:1rem; text-align:center;">
          <strong>Clube:</strong> <?= htmlspecialchars($club->club_name) ?>
        </div>
        <div class="create-product-card" style="background:#1a0d0d; border:1px solid #333; border-radius:12px; padding:1rem; box-shadow:0 10px 25px rgba(0,0,0,0.3);">
          <h3 style="font-family:'Bangers', cursive; color:#ff3333; margin:0 0 1rem 0;">Cadastrar Produto</h3>
          <form action="<?= url('app/meuclube/produto/criar'); ?>" method="post" enctype="multipart/form-data" style="display:grid; grid-template-columns:repeat(2,1fr); gap:12px;">
            <div class="form-group">
              <label for="add-name">Nome do Produto:</label>
              <input type="text" id="add-name" name="name" required autocomplete="off">
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
            <div class="form-group" style="grid-column:1/-1;">
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
            <div class="form-group" style="grid-column:1/-1;">
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
            <div style="grid-column:1/-1; text-align:right;">
              <button type="submit" class="btn" style="padding:0.6rem 1rem; border-radius:10px; background:#ff3333; color:#000; border:none; font-family:'Bangers', cursive;">Adicionar Produto</button>
            </div>
          </form>
        </div>
        <div style="margin-top:1.5rem;">
          <h3 style="font-family:'Bangers', cursive; color:#ff3333; margin-bottom:0.8rem;">Meus Produtos</h3>
          <?php if (empty($products)): ?>
            <p style="color:#a0a0a0;">Nenhum produto cadastrado.</p>
          <?php else: ?>
            <table class="custom-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nome</th>
                  <th>Preço</th>
                  <th>Estoque</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products as $p): ?>
                  <tr>
                    <td><?= (int)$p->id ?></td>
                    <td><?= htmlspecialchars($p->name) ?></td>
                    <td>R$ <?= number_format((float)$p->price, 2, ',', '.') ?></td>
                    <td><?= (int)$p->stock ?></td>
                    <td>
                      <form action="<?= url('app/meuclube/produto/editar'); ?>" method="post" style="display:inline-block; margin-right:8px;">
                        <input type="hidden" name="id" value="<?= (int)$p->id ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($p->name) ?>" style="width:160px;">
                        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($p->price) ?>" style="width:110px;">
                        <input type="number" name="stock" value="<?= (int)$p->stock ?>" style="width:90px;">
                        <label style="margin-left:6px; font-size:0.9rem;">
                          <input type="checkbox" name="is_active" value="1" <?= ((int)$p->is_active === 1) ? 'checked' : '' ?>> Ativo
                        </label>
                        <button type="submit" class="btn btn-secondary" style="margin-left:6px;">Salvar</button>
                      </form>
                      <form action="<?= url('app/meuclube/produto/excluir'); ?>" method="post" style="display:inline-block;">
                        <input type="hidden" name="id" value="<?= (int)$p->id ?>">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Excluir este produto?');">Excluir</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<?php $this->start("post-scripts"); ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const params = new URLSearchParams(window.location.search);
  const success = params.get('success');
  const error = params.get('error');
  var toggleBtn = document.getElementById('toggle-create-club');
  var formClub = document.getElementById('create-club-form');
  if (toggleBtn && formClub) {
    toggleBtn.addEventListener('click', function(){
      formClub.style.display = (formClub.style.display === 'none' || formClub.style.display === '') ? 'block' : 'none';
    });
  }
  if (success && typeof window.showToast === 'function') {
    switch(success){
      case 'club_created': window.showToast('Clube criado com sucesso!', 'success'); break;
      case 'product_created': window.showToast('Produto criado com sucesso!', 'success'); break;
      case 'product_updated': window.showToast('Produto atualizado com sucesso!', 'success'); break;
      case 'product_deleted': window.showToast('Produto excluído com sucesso!', 'success'); break;
    }
  }
  if (error && typeof window.showToast === 'function') {
    switch(error){
      case 'forbidden': window.showToast('Apenas vendedores podem acessar Meu Clube.', 'error'); break;
      case 'already_has_club': window.showToast('Você já possui um clube.', 'error'); break;
      case 'club_not_found': window.showToast('Nenhum clube encontrado para sua conta.', 'error'); break;
      case 'club_create_failed': window.showToast('Falha ao criar clube.', 'error'); break;
      case 'create_failed': window.showToast('Falha ao criar produto.', 'error'); break;
      case 'update_failed': window.showToast('Falha ao atualizar produto.', 'error'); break;
      case 'delete_failed': window.showToast('Falha ao excluir produto.', 'error'); break;
      default: window.showToast('Ocorreu um erro.', 'error'); break;
    }
  }
});
</script>
<?php $this->end(); ?>
