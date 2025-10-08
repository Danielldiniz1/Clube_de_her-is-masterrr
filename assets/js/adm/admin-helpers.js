/**
 * Displays a toast notification.
 * @param {string} message The message to display.
 * @param {string} type 'success', 'successo', or 'error'.
 */
export function showToast(message, type = 'success') {
    const container = document.body;
    let toastContainer = document.getElementById('toast-container');

    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        container.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    const toastClass = (type === 'error') ? 'toast error' : 'toast';
    toast.className = toastClass;
    toast.textContent = message;
    
    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.remove();
        if (toastContainer.childElementCount === 0) {
            toastContainer.remove();
        }
    }, 4000);
}

/**
 * A simple API client for admin operations.
 */
export const adminApi = {
    baseUrl: 'http://localhost/Clube_de_her-is-master/api',

    async request(endpoint, method = 'GET', data = null) {
        const url = `${this.baseUrl}${endpoint}`;
        const config = {
            method: method,
            headers: {}
        };

        if (data) {
            // Para POST e PUT, os dados são enviados como x-www-form-urlencoded
            config.body = new URLSearchParams(data);
            config.headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        try {
            const response = await fetch(url, config);
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || result.mensagem || `HTTP error! status: ${response.status}`);
            }
            return result;
        } catch (error) {
            console.error(`API Error on ${method} ${endpoint}:`, error);
            showToast(error.message || 'Falha na comunicação com o servidor.', 'error');
            throw error;
        }
    }
};