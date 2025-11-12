import Toast from '../../class/Toast.js';
import HttpCart from '../../class/HttpCart.js';

document.addEventListener('DOMContentLoaded', () => {
  const toast = new Toast();
  const cart = new HttpCart();

  // Intercepta formulário de atualizar quantidade
  document.querySelectorAll('form[action*="carrinho/atualizar"]').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const productId = parseInt(form.querySelector('input[name="product_id"]').value, 10);
      const quantity = parseInt(form.querySelector('input[name="quantity"]').value, 10);
      try {
        const res = await cart.updateItem(productId, quantity);
        toast.fromApi(res);
        // Recarrega para refletir as mudanças do servidor
        window.location.reload();
      } catch (err) {
        toast.show(err.message || 'Erro ao atualizar item.', 'error');
      }
    });
  });

  // Intercepta formulário de remover item
  document.querySelectorAll('form[action*="carrinho/remover"]').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const productId = parseInt(form.querySelector('input[name="product_id"]').value, 10);
      try {
        const res = await cart.removeItem(productId);
        toast.fromApi(res);
        window.location.reload();
      } catch (err) {
        toast.show(err.message || 'Erro ao remover item.', 'error');
      }
    });
  });

  // Intercepta formulário de limpar carrinho
  document.querySelectorAll('form[action*="carrinho/limpar"]').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      try {
        const res = await cart.clear();
        toast.fromApi(res);
        window.location.reload();
      } catch (err) {
        toast.show(err.message || 'Erro ao limpar carrinho.', 'error');
      }
    });
  });
});