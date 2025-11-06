// Renderização e gerenciamento do carrinho via API na página /app/carrinho
import HttpClientBase from '../../class/HttpClientBase.js';

function getBaseUrl() {
  const origin = window.location.origin;
  const path = window.location.pathname;
  const match = path.match(/^\/Clube_de_her-is-master\b/);
  const basePath = match ? match[0] : '';
  return `${origin}${basePath}`;
}

const BASE_URL = getBaseUrl();

function formatBRL(value) {
  const num = Number(value) || 0;
  return num.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function resolveImage(path) {
  if (!path) return `${BASE_URL}/assets/img/imagem.jpg`;
  try {
    // Se path já for uma URL absoluta
    const u = new URL(path, BASE_URL);
    return u.href;
  } catch (_) {
    return `${BASE_URL}/assets/img/imagem.jpg`;
  }
}

async function renderCart() {
  const token = localStorage.getItem('token');
  const api = new HttpClientBase(`${BASE_URL}/api/cart/`);
  if (token) api.setAuthToken(token);

  const tbody = document.getElementById('cart-body');
  const totalEl = document.getElementById('cart-total');
  const emptyEl = document.getElementById('cart-empty');
  const tableEl = document.getElementById('cart-table');

  if (!tbody || !totalEl) return;

  tbody.innerHTML = '';
  let total = 0;

  try {
    const response = await api.get('items');
    const cartItems = response?.data?.items || [];

    // Se não autenticado, exibir aviso e não marcar como vazio genérico
    if (response?.status === 'unauthorized') {
      if (tableEl) tableEl.style.display = 'none';
      if (emptyEl) {
        emptyEl.style.display = 'block';
        emptyEl.querySelector('p').textContent = 'Faça login para visualizar seu carrinho.';
      }
      totalEl.textContent = 'Total: R$ 0,00';
      return;
    }

    if (!cartItems.length) {
      if (tableEl) tableEl.style.display = 'none';
      if (emptyEl) emptyEl.style.display = 'block';
      totalEl.textContent = 'Total: R$ 0,00';
      return;
    } else {
      if (tableEl) tableEl.style.display = 'table';
      if (emptyEl) emptyEl.style.display = 'none';
    }

    cartItems.forEach((item) => {
      const subtotal = (Number(item.price) || 0) * (Number(item.quantity) || 1);
      total += subtotal;
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>
          <div class="product-info">
          <img src="${resolveImage(item.image_path)}" alt="${item.name}">
          <span>${item.name}</span>
          </div>
        </td>
        <td>R$ ${formatBRL(item.price)}</td>
        <td>
        <input type="number" min="1" value="${item.quantity}" data-product-id="${item.product_id}" class="qty-input">
        </td>
        <td>R$ ${formatBRL(subtotal)}</td>
      <td><button class="btn btn-secondary remove-btn" data-product-id="${item.product_id}">X</button></td>
      `;
      tbody.appendChild(tr);
    });

    totalEl.textContent = `Total: R$ ${formatBRL(total)}`;
  } catch (error) {
    console.error('Falha ao carregar carrinho:', error);
    if (tableEl) tableEl.style.display = 'none';
    if (emptyEl) emptyEl.style.display = 'block';
    totalEl.textContent = 'Total: R$ 0,00';
  }
}

async function updateQuantity(productId, qty) {
  const token = localStorage.getItem('token');
  const api = new HttpClientBase(`${BASE_URL}/api/cart/`);
  if (token) api.setAuthToken(token);

  try {
    // Enviar como x-www-form-urlencoded via FormData para compatibilidade
    const form = new FormData();
    form.append('quantity', Math.max(1, Number(qty) || 1));
    const res = await api.put(`item/${productId}`, form);
    if (res?.status !== 'success') throw new Error(res?.message || 'Falha ao atualizar');
    renderCart();
  } catch (error) {
    console.error('Erro ao atualizar quantidade:', error);
    alert(error.message || 'Não foi possível atualizar a quantidade.');
  }
}

async function removeItem(productId) {
  const token = localStorage.getItem('token');
  const api = new HttpClientBase(`${BASE_URL}/api/cart/`);
  if (token) api.setAuthToken(token);

  try {
    const res = await api.delete(`item/${productId}`);
    if (res?.status !== 'success') throw new Error(res?.message || 'Falha ao remover');
    renderCart();
  } catch (error) {
    console.error('Erro ao remover item:', error);
    alert(error.message || 'Não foi possível remover o item.');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  renderCart();

  document.addEventListener('input', (e) => {
    if (e.target && e.target.classList.contains('qty-input')) {
      const productId = e.target.getAttribute('data-product-id');
      updateQuantity(productId, e.target.value);
    }
  });

  document.addEventListener('click', (e) => {
    if (e.target && e.target.classList.contains('remove-btn')) {
      const productId = e.target.getAttribute('data-product-id');
      removeItem(productId);
    }
  });
});