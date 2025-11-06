// Carrinho de compras usando API e banco (adicionar item e redirecionar)
import HttpClientBase from '../../class/HttpClientBase.js';

const BASE_URL = `${window.location.origin}/Clube_de_her-is-master`;

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
    window.location.href = `${BASE_URL}/app/carrinho`;
  } catch (error) {
    console.error('Erro ao adicionar ao carrinho:', error);
    alert(error.message || 'Não foi possível adicionar ao carrinho. Tente novamente.');
  }
}

// Disponibiliza no escopo global para uso em onclick dos botões
window.addToCart = addToCart;