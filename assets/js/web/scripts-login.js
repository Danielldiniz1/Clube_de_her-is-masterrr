import HttpClientBase from '../../class/HttpClientBase.js';

const form = document.getElementById("formLogin");
const emailInput = document.getElementById("email");
const senhaInput = document.getElementById("password");
const toastContainer = document.getElementById("toast-container");


const api = new HttpClientBase("http://localhost/Clube_de_her-is-master/api/users/"); 

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
            localStorage.setItem("token", response.data.user.token);
            localStorage.setItem("userId", JSON.stringify(response.data.user.id));
            localStorage.setItem("dataUser", JSON.stringify(response.data.user));
 
            const currentUrl = window.location.href;
            // Substitui 'login' no final da URL por 'app' para redirecionar
            const appUrl = currentUrl.replace(/login\/?$/, 'app');
            window.location.href = appUrl;

        } else {
            showToast(response.message || "Ocorreu um erro inesperado.", 'error');
        }
    } catch (error) {
        showToast(error.message || "Ops! Não foi possível conectar ao servidor.", 'error');
        console.error("Erro no login:", error);
    }
});
