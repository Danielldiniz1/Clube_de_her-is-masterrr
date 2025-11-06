import HttpClientBase from '../../class/HttpClientBase.js';

const form = document.getElementById("formLogin");
const emailInput = document.getElementById("email");
const senhaInput = document.getElementById("password");
const toastContainer = document.getElementById("toast-container");


// Use the current origin to avoid host mismatches (localhost vs 127.0.0.1)
const apiBase = `${window.location.origin}/api/users/`;
const api = new HttpClientBase(apiBase); 

/**
 * Exibe uma notificação toast na tela.
 * @param {string} message A mensagem a ser exibida.
 * @param {string} type O tipo de toast ('success' ou 'error').
 */
const showToast = (message, type = 'error') => {
    if (!toastContainer) return;
    const toastClass = type === 'error' ? 'toast error' : 'toast';
    
    const toastElement = document.createElement('div');
    toastElement.className = toastClass;
    toastElement.textContent = message;

    toastContainer.innerHTML = '';
    toastContainer.appendChild(toastElement);
};

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const email = emailInput.value.trim();
    const password = senhaInput.value;

    const formdata = new FormData();
    formdata.append("email", email);
    formdata.append("password", password);

    try {
        const response = await api.post("login", formdata);

        if (response.type === "success") {
            const token = response.data.user.token;
            localStorage.setItem("token", token);
            localStorage.setItem("userId", JSON.stringify(response.data.user.id));
            localStorage.setItem("dataUser", JSON.stringify(response.data.user));

            // Grava cookie para autenticação server-side (expira em ~90min)
            const expires = new Date(Date.now() + 90 * 60 * 1000).toUTCString();
            document.cookie = `token=${token}; expires=${expires}; path=/`;

            // Redirect reliably to /app on the same origin
            window.location.href = `${window.location.origin}/app`;

        } else {
            showToast(response.message || "Ocorreu um erro inesperado.", 'error');
        }
    } catch (error) {
        showToast(error.message || "Ops! Não foi possível conectar ao servidor.", 'error');
        console.error("Erro no login:", error);
    }
});
