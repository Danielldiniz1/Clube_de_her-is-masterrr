// Elementos do DOM
const openModalBtn = document.getElementById("openEditProfileModal");
const modal = document.getElementById("editProfileModal");
const closeModalBtn = document.getElementById("cancelEditProfile");
const form = document.getElementById("editProfileForm");
const toastContainer = document.getElementById("toast-container");

// Elementos de exibição estática no perfil
const staticUserName = document.getElementById("static-name");
const staticUserEmail = document.getElementById("static-email");
const staticUserIdType = document.getElementById("static-idType");

// Função para exibir notificações
const showToast = (message, type = 'success') => {
    if (!toastContainer) return;
    const toast = document.createElement('div');
    toast.className = `toast ${type === 'error' ? 'error' : ''}`;
    toast.textContent = message;
    toastContainer.appendChild(toast);

    // Remove o toast após alguns segundos
    setTimeout(() => {
        toast.remove();
    }, 4000);
};

// Abrir o modal
if (openModalBtn) {
    openModalBtn.addEventListener("click", () => {
        if (modal) modal.style.display = "flex";
    });
}

// Fechar o modal
if (closeModalBtn) {
    closeModalBtn.addEventListener("click", () => {
        if (modal) modal.style.display = "none";
    });
}

// Fechar o modal clicando fora dele
window.addEventListener("click", (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

// Lidar com o envio do formulário
if (form) {
    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        const token = localStorage.getItem("token");

        if (!token) {
            showToast("Erro de autenticação. Faça login novamente.", "error");
            return;
        }

        const myHeaders = new Headers();
        myHeaders.append("Authorization", `Bearer ${token}`);

        const requestOptions = {
            method: "POST",
            headers: myHeaders,
            body: formData,
            redirect: "follow"
        };

        try {
            const response = await fetch("http://localhost/Clube_de_her-is-master/api/users/profile", requestOptions);
            const result = await response.json();

            if (result.type === "success") {
                showToast(result.message || "Perfil atualizado com sucesso!");
                
                // Atualiza os dados na página sem recarregar
                staticUserName.textContent = formData.get("name");
                staticUserEmail.textContent = formData.get("email");
                const idType = formData.get("idType");
                staticUserIdType.textContent = idType == 1 ? 'Vendedor' : 'Cliente';

                if (modal) modal.style.display = "none";
            } else {
                showToast(result.message || "Ocorreu um erro.", "error");
            }
        } catch (error) {
            console.error("Erro ao atualizar perfil:", error);
            showToast("Falha na comunicação com o servidor.", "error");
        }
    });
}
