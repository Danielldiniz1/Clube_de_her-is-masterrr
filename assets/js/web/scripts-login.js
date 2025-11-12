import HttpUser from '../../class/HttpUser.js';
import Toast from '../../class/Toast.js';

const form = document.getElementById("formLogin");
const emailInput = document.getElementById("email");
const senhaInput = document.getElementById("password");
const toastContainer = document.getElementById("toast-container");


// Base confiável fornecida pelo servidor via window.__APP_BASE
const APP_BASE = typeof window.__APP_BASE === 'string' ? window.__APP_BASE : `${window.location.origin}`;
const api = new HttpUser(`${APP_BASE}/api/users`);
const toast = new Toast();

/**
 * Exibe uma notificação toast na tela.
 * @param {string} message A mensagem a ser exibida.
 * @param {string} type O tipo de toast ('success' ou 'error').
 */
// Prefer class-based toast; fallback to global
const showToast = (message, type = 'error') => toast.show(message, type);

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const email = emailInput.value.trim();
    const password = senhaInput.value;

    const formdata = new FormData();
    formdata.append("email", email);
    formdata.append("password", password);

    try {
        const response = await api.login(formdata);

        if (response.type === "success") {
            const token = response.data.user.token;
            localStorage.setItem("token", token);
            localStorage.setItem("userId", JSON.stringify(response.data.user.id));
            localStorage.setItem("dataUser", JSON.stringify(response.data.user));

            // Grava cookie para autenticação server-side (expira em ~90min)
            const expires = new Date(Date.now() + 90 * 60 * 1000).toUTCString();
            document.cookie = `token=${token}; expires=${expires}; path=/`;

            // Redireciona para /app respeitando subpasta do projeto
            window.location.href = `${APP_BASE}/app`;

        } else {
            showToast(response.message || "Ocorreu um erro inesperado.", response.type || 'error');
        }
    } catch (error) {
        showToast(error.message || "Ops! Não foi possível conectar ao servidor.", 'error');
        console.error("Erro no login:", error);
    }
});
