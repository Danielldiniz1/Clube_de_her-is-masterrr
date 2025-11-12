export default class Toast {
  constructor() {}

  show(message, type = 'success') {
    // Reuse global showToast if present; else fallback
    if (typeof window !== 'undefined' && typeof window.showToast === 'function') {
      window.showToast(message, type);
      return;
    }

    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = `toast ${type}`; // expects .toast.success, .toast.error, .toast.warning
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => {
      toast.remove();
      if (container.childElementCount === 0) {
        container.remove();
      }
    }, 4000);
  }

  fromApi(response) {
    // response:
    // { type: 'success'|'error'|'warning', message: '...', data?: any }
    const type = response?.type || response?.status || 'error';
    const message = response?.message || 'Ocorreu um erro.';
    this.show(message, type);
  }
}