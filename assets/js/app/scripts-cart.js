// Carrinho de compras usando API e banco (adicionar item e redirecionar)
import HttpClientBase from '../../class/HttpClientBase.js';

function getBaseUrl() {
  const origin = window.location.origin;
  const path = window.location.pathname;
  const match = path.match(/^\/Clube_de_her-is-master\b/);
  const basePath = match ? match[0] : '';
  return `${origin}${basePath}`;
}

const BASE_URL = getBaseUrl();

function ensureToastStyles() {
  if (document.getElementById('toast-styles')) return;
  const style = document.createElement('style');
  style.id = 'toast-styles';
  style.textContent = `
    .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
    .toast { padding: 12px 16px; border-radius: 10px; color: #000; font-family: 'Roboto', sans-serif; box-shadow: 0 6px 18px rgba(0,0,0,0.3); opacity: 0; transform: translateY(-6px); transition: opacity .2s ease, transform .2s ease; }
    .toast.show { opacity: 1; transform: translateY(0); }
    .toast-success { background: #4caf50; }
    .toast-error { background: #ff3333; }
  `;
  document.head.appendChild(style);
}

function showToast(message, type = 'success') {
  ensureToastStyles();
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.textContent = message;
  container.appendChild(toast);
  // trigger animation
  requestAnimationFrame(() => toast.classList.add('show'));
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 200);
  }, 2500);
}

export async function addToCart(productId, quantity = 1) {
  const token = localStorage.getItem('token');
  if (!token) {
    alert('Você precisa estar logado para adicionar ao carrinho.');
    window.location.href = `${BASE_URL}/login`;
    return;
  }

  const api = new HttpClientBase(`${BASE_URL}/api/cart/`);
  api.setAuthToken(token);

  try {
    // Enviar como FormData para compatibilidade com PHP ($_POST)
    const form = new FormData();
    form.append('product_id', Number(productId));
    form.append('quantity', Number(quantity));
    const result = await api.post('add', form);
    if (result?.status !== 'success') {
      throw new Error(result?.message || 'Falha ao adicionar ao carrinho');
    }
    showToast('Produto adicionado ao carrinho!', 'success');
  } catch (error) {
    console.error('Erro ao adicionar ao carrinho:', error);
    showToast(error.message || 'Não foi possível adicionar ao carrinho.', 'error');
  }
}

// Disponibiliza no escopo global para uso em onclick dos botões
window.addToCart = addToCart;
window.showToast = showToast;