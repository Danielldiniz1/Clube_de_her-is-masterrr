// Global toast utility and URL param-based messages for public pages
(() => {
  const getToastContainer = () => {
    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      document.body.appendChild(container);
    }
    return container;
  };

  const showToast = (message, type = 'success') => {
    const container = getToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast ${type === 'error' ? 'error' : ''}`;
    toast.textContent = message;
    container.appendChild(toast);

    // Auto-remove after animation duration (~4s)
    setTimeout(() => {
      toast.remove();
      if (container.childElementCount === 0 && container.parentNode === document.body) {
        // Leave container for repeated toasts if originally present in DOM; otherwise clean up
        container.remove();
      }
    }, 4000);
  };

  // Expose globally for other scripts
  window.showToast = showToast;

  document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const success = params.get('success');
    const error = params.get('error');

    if (success) {
      switch (success) {
        case 'reset_sent':
          showToast('Código enviado com sucesso! Verifique seu e-mail.', 'success');
          break;
        case 'password_reset':
          showToast('Senha atualizada com sucesso!', 'success');
          break;
        case 'added_cart':
          showToast('Produto adicionado ao carrinho!', 'success');
          break;
        case 'added_wishlist':
          showToast('Produto adicionado à lista de desejos!', 'success');
          break;
        case 'removed_wishlist':
          showToast('Produto removido da lista de desejos!', 'success');
          break;
        case 'removed_cart':
          showToast('Produto removido do carrinho!', 'success');
          break;
        case 'cleared_cart':
          showToast('Carrinho limpo com sucesso!', 'success');
          break;
        case 'purchase_complete':
          showToast('Compra finalizada! Comprovante enviado ao seu e-mail.', 'success');
          break;
        case 'club_created':
          showToast('Clube criado com sucesso!', 'success');
          break;
        case 'product_created':
          showToast('Produto criado com sucesso!', 'success');
          break;
        case 'product_updated':
          showToast('Produto atualizado com sucesso!', 'success');
          break;
        case 'product_deleted':
          showToast('Produto excluído com sucesso!', 'success');
          break;
        default:
          showToast('Produto adicionado ao carrinho', 'success');
          break;
      }
    }

    if (error) {
      switch (error) {
        case 'send_failed':
          showToast('Não foi possível enviar o código. Tente novamente.', 'error');
          break;
        case 'server_error':
          showToast('Ocorreu um erro no servidor. Tente novamente.', 'error');
          break;
        case 'invalid_token':
          showToast('Link inválido ou expirado.', 'error');
          break;
        case 'mismatch':
          showToast('As senhas não correspondem.', 'error');
          break;
        case 'user_not_found':
          showToast('Usuário não encontrado.', 'error');
          break;
        case 'empty_cart':
          showToast('Seu carrinho está vazio.', 'error');
          break;
        case 'email_failed':
          showToast('Falha ao enviar comprovante. Tente novamente.', 'error');
          break;
        case 'already_has_club':
          showToast('Você já possui um clube.', 'error');
          break;
        case 'club_create_failed':
          showToast('Falha ao criar clube.', 'error');
          break;
        case 'forbidden':
          showToast('Apenas vendedores podem acessar Meu Clube.', 'error');
          break;
        case 'club_not_found':
          showToast('Nenhum clube encontrado para sua conta.', 'error');
          break;
        case 'create_failed':
          showToast('Falha ao criar produto.', 'error');
          break;
        case 'update_failed':
          showToast('Falha ao atualizar produto.', 'error');
          break;
        case 'delete_failed':
          showToast('Falha ao excluir produto.', 'error');
          break;
        default:
          showToast('Ocorreu um erro. Tente novamente.', 'error');
          break;
      }
    }
  });
})();
