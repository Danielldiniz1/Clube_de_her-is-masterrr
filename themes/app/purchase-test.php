<?php
echo $this->layout("_theme");
?>

<div class="container">
    <h2>Teste de Fluxo de Compra (API)</h2>
    <?php if (empty($user) || empty($user->id)): ?>
        <p>Você precisa estar logado para executar os testes.</p>
        <a href="<?= url('login'); ?>" class="btn">Ir para Login</a>
    <?php else: ?>
    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:15px;">
        <button id="loadProducts" class="btn">Carregar Produtos</button>
        <button id="addFirst" class="btn">Adicionar 1º produto</button>
        <button id="updateFirst" class="btn">Atualizar quantidade p/ 2</button>
        <button id="removeFirst" class="btn" style="background:#ff3333; color:#000;">Remover 1º produto</button>
        <button id="clearCart" class="btn" style="background:#1a0d0d;">Limpar Carrinho</button>
    </div>
    <pre id="log" style="background:#111; color:#eee; padding:10px; border-radius:8px; min-height:160px;">
Instruções:
- Clique em "Carregar Produtos" para listar e pegar o primeiro ID.
- Use "Adicionar 1º produto" para inserir no carrinho.
- "Atualizar quantidade" ajusta para 2.
- "Remover 1º produto" remove apenas este item.
- "Limpar Carrinho" zera o carrinho.
    </pre>
    <?php endif; ?>
</div>

<?php $this->start("post-scripts"); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const log = document.getElementById('log');
  const btnLoad = document.getElementById('loadProducts');
  const btnAdd = document.getElementById('addFirst');
  const btnUpdate = document.getElementById('updateFirst');
  const btnRemove = document.getElementById('removeFirst');
  const btnClear = document.getElementById('clearCart');

  const basePath = (() => {
    const parts = window.location.pathname.split('/').filter(Boolean);
    return parts.length > 0 ? `/${parts[0]}` : '';
  })();
  const apiBaseCart = `${window.location.origin}${basePath}/api/cart`;
  const apiBaseProducts = `${window.location.origin}${basePath}/api/products`;

  function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
  }
  const token = getCookie('token');
  const authHeader = token ? { 'Authorization': 'Bearer ' + token } : {};

  let firstProductId = null;

  function append(msg, obj) {
    log.textContent += `\n${msg}`;
    if (obj !== undefined) {
      try { log.textContent += `\n${JSON.stringify(obj, null, 2)}`; } catch(e) {}
    }
  }

  async function loadProducts() {
    append('> Carregando produtos...');
    try {
      const res = await fetch(`${apiBaseProducts}/`, { headers: authHeader });
      const data = await res.json();
      const list = Array.isArray(data?.data) ? data.data : (Array.isArray(data) ? data : []);
      if (list.length > 0) {
        firstProductId = list[0].id || list[0].product_id || null;
        append(`✓ Produtos carregados. Primeiro ID: ${firstProductId}`);
      } else {
        append('! Nenhum produto encontrado.');
      }
    } catch (err) {
      append('x Erro ao carregar produtos:', err);
    }
  }

  async function addFirst() {
    if (!firstProductId) return append('! Primeiro produto não definido.');
    append(`> Adicionando produto ${firstProductId} ao carrinho...`);
    try {
      const body = new URLSearchParams({ product_id: String(firstProductId), quantity: '1' });
      const res = await fetch(`${apiBaseCart}/add`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', ...authHeader },
        body
      });
      const data = await res.json();
      append('✓ Adicionado com sucesso.', data);
    } catch (err) {
      append('x Erro ao adicionar:', err);
    }
  }

  async function updateFirst() {
    if (!firstProductId) return append('! Primeiro produto não definido.');
    append(`> Atualizando quantidade do produto ${firstProductId} para 2...`);
    try {
      const body = new URLSearchParams({ product_id: String(firstProductId), quantity: '2' });
      const res = await fetch(`${apiBaseCart}/updateItem`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', ...authHeader },
        body
      });
      const data = await res.json();
      append('✓ Quantidade atualizada.', data);
    } catch (err) {
      append('x Erro ao atualizar:', err);
    }
  }

  async function removeFirst() {
    if (!firstProductId) return append('! Primeiro produto não definido.');
    append(`> Removendo produto ${firstProductId} do carrinho...`);
    try {
      const body = new URLSearchParams({ product_id: String(firstProductId) });
      const res = await fetch(`${apiBaseCart}/removeItem`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', ...authHeader },
        body
      });
      const data = await res.json();
      append('✓ Produto removido.', data);
    } catch (err) {
      append('x Erro ao remover:', err);
    }
  }

  async function clearCart() {
    append('> Limpando carrinho...');
    try {
      const res = await fetch(`${apiBaseCart}/clear`, {
        method: 'POST',
        headers: { ...authHeader }
      });
      const data = await res.json();
      append('✓ Carrinho limpo.', data);
    } catch (err) {
      append('x Erro ao limpar carrinho:', err);
    }
  }

  btnLoad && btnLoad.addEventListener('click', loadProducts);
  btnAdd && btnAdd.addEventListener('click', addFirst);
  btnUpdate && btnUpdate.addEventListener('click', updateFirst);
  btnRemove && btnRemove.addEventListener('click', removeFirst);
  btnClear && btnClear.addEventListener('click', clearCart);
});
</script>
<?php $this->end(); ?>