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
        default:
          showToast('Operação concluída com sucesso.', 'success');
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
        default:
          showToast('Ocorreu um erro. Tente novamente.', 'error');
          break;
      }
    }
  });
})();