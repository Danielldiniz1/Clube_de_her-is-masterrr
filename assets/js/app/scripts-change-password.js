const showModal = document.getElementById("change");    
const modal = document.getElementById("changePasswordModal");         
const closeModal = document.getElementById("cancel");    
const form = modal.querySelector("form");   



const currentPasswordInput = document.getElementById("currentPassword");
const newPasswordInput = document.getElementById("newPassword");
const confirmPasswordInput = document.getElementById("confirmPassword");

let resultBox = document.createElement("div");
resultBox.id = "password-result";
resultBox.style.marginTop = "10px";
form.appendChild(resultBox);

const user = JSON.parse(localStorage.getItem("dataUser"));
const token = localStorage.getItem("token");

showModal.addEventListener("click", (event) => {
  event.preventDefault();
  modal.style.display = "flex";
});

closeModal.addEventListener("click", () => {
  modal.style.display = "none";
  resultBox.textContent = "";
});

form.addEventListener("submit", async (event) => {
  event.preventDefault();

  const currentPassword = currentPasswordInput.value;
  const newPassword = newPasswordInput.value;
  const confirmPassword = confirmPasswordInput.value;

  const formData = new FormData();
  formData.append("password", currentPassword);
  formData.append("newPassword", newPassword);
  formData.append("confirmNewPassword", confirmPassword);

  const myHeaders = new Headers();
  myHeaders.append("token", token);
  myHeaders.append("Authorization", `Bearer ${token}`);

  const requestOptions = {
    method: "POST",
    headers: myHeaders,
    body: formData,
    redirect: "follow"
  };

  try {
    const response = await fetch("http://localhost/Clube_de_her-is-master/api/users/set-password", requestOptions);
    const data = await response.json();
    console.log("📄 Resposta da API:", data);

    if (data.type === "success") {
      resultBox.textContent = "Senha alterada com sucesso!";
      resultBox.style.color = "green";
      setTimeout(() => {
        modal.style.display = "none";
        resultBox.textContent = "";
        form.reset();
      }, 2000);
    } else {
      resultBox.textContent = data.message || "Erro ao atualizar senha.";
      resultBox.style.color = "red";
    }
  } catch (error) {
    console.error("Erro:", error);
    resultBox.textContent = "Erro ao atualizar senha.";
    resultBox.style.color = "red";}
});