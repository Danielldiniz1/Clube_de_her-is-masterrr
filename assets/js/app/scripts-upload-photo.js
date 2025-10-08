document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formPhotoUpload");
    const photoInput = document.getElementById("photo");
    const profilePic = document.getElementById("profile-pic");
    const toastContainer = document.getElementById("toast-container");
    const fileNameSpan = document.querySelector('.file-name');

    if (photoInput && fileNameSpan) {
        photoInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                fileNameSpan.textContent = e.target.files[0].name;
            } else {
                fileNameSpan.textContent = 'Nenhum arquivo selecionado';
            }
        });
    }

    // Função para exibir notificações (pode ser movida para um arquivo compartilhado)
    const showToast = (message, type = 'success') => {
        if (!toastContainer) return;
        const toast = document.createElement('div');
        toast.className = `toast ${type === 'error' ? 'error' : ''}`;
        toast.textContent = message;
        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 4000);
    };

    if (form) {
        form.addEventListener("submit", async (event) => {
            event.preventDefault();

            const token = localStorage.getItem("token");
            if (!token) {
                showToast("Erro de autenticação. Faça login novamente.", "error");
                return;
            }

            if (!photoInput.files || photoInput.files.length === 0) {
                showToast("Por favor, selecione uma imagem.", "error");
                return;
            }

            const formData = new FormData();
            formData.append("photo", photoInput.files[0]);

            const myHeaders = new Headers();
            myHeaders.append("Authorization", `Bearer ${token}`);

            try {
                const response = await fetch("http://localhost/Clube_de_her-is-master/api/users/photo", {
                    method: "POST",
                    headers: myHeaders,
                    body: formData,
                });

                const result = await response.json();

                if (result.type === "success") {
                    showToast(result.message || "Foto atualizada com sucesso!");
                    if (profilePic && result.data?.user?.photo) {
                        // Atualiza a imagem na página dinamicamente
                        profilePic.src = `http://localhost/Clube_de_her-is-master/${result.data.user.photo}?t=${new Date().getTime()}`;
                    }
                } else {
                    showToast(result.message || "Ocorreu um erro ao enviar a foto.", "error");
                }
            } catch (error) {
                console.error("Erro no upload da foto:", error);
                showToast("Falha na comunicação com o servidor.", "error");
            }
        });
    }
});