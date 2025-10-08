console.log("oi");

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formRegister");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); 

        const formData = new FormData(form);

        const requestOptions = {
            method: "POST",
            body: formData,
            redirect: "follow"
        };

        fetch("http://localhost/Clube_de_her-is-master/api/users/register", requestOptions)
            .then(response => response.json())
            .then(result => {
                console.log(result);

                const toastContainer = document.getElementById("toast-container");
                const message = result.message || "Ocorreu um erro inesperado.";
                let toastClass = "toast"; // Classe base para o toast (verde por padrão)

                if (result.type === "error") {
                    toastClass += " error"; // Adiciona a classe de erro (vermelho)
                } else if (result.type === "success") {
                    form.reset(); // Limpa o formulário em caso de sucesso
                }

                toastContainer.innerHTML = `<div class="${toastClass}">${message}</div>`;

            })
            .catch(error => {
                console.error("Erro ao cadastrar:", error);
                const toastContainer = document.getElementById("toast-container");
                toastContainer.innerHTML = `<div class="toast error">Erro de comunicação ao tentar cadastrar.</div>`;
            });
    });
});
