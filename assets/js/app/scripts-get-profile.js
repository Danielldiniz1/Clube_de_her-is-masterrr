// Use an Immediately Invoked Function Expression (IIFE) to use async/await
(async () => {
  // Static fields on the page
  const staticUserName = document.getElementById("static-name");
  const staticUserEmail = document.getElementById("static-email");
  const staticUserIdType = document.getElementById("static-idType");
  const profilePic = document.getElementById("profile-pic"); // Pega o elemento da imagem

  // Form fields in the modal
  const userNameInput = document.getElementById("name");
  const userEmailInput = document.getElementById("email");
  const userIdTypeSelect = document.getElementById("idType");

  const id = localStorage.getItem("userId");
  const token = localStorage.getItem("token");

  if (!id || !token) {
    staticUserName.textContent = "Authentication Error.";
    staticUserEmail.textContent = "Please log in again.";
    staticUserIdType.textContent = "-";
    console.error("User ID or token not found in localStorage.");
    return;
  }

  const myHeaders = new Headers();
  myHeaders.append("token", token);
  myHeaders.append("Authorization", `Bearer ${token}`);

  const requestOptions = {
    method: "GET",
    headers: myHeaders,
    redirect: "follow"
  };

  try {
    const response = await fetch(`http://localhost/Clube_de_her-is-master/api/users/${id}`, requestOptions);
    if (!response.ok) {
      throw new Error(`Network response was not ok: ${response.statusText}`);
    }
    const result = await response.json();

    if (result.type === "success" && result.data?.user) {
      const user = result.data.user;

      // Populate static display area
      staticUserName.textContent = user.name;
      staticUserEmail.textContent = user.email;
      staticUserIdType.textContent = user.idType == 1 ? 'Vendedor' : 'Cliente';

      // Popula a foto de perfil
      if (profilePic) {
        if (user.photo) {
          // Constrói a URL completa para a foto.
          // Assumindo que `user.photo` é um caminho relativo como 'uploads/images/users/foto.jpg'
          profilePic.src = `http://localhost/Clube_de_her-is-master/${user.photo}`;
        }
      }

      // Populate the modal form, only if the elements exist
      if (userNameInput) userNameInput.value = user.name;
      if (userEmailInput) userEmailInput.value = user.email;
      if (userIdTypeSelect) userIdTypeSelect.value = user.idType;
    } else {
      throw new Error(result.message || "Could not load profile data.");
    }
  } catch (error) {
    console.error("Error fetching profile data:", error);
    staticUserName.textContent = "Error loading profile.";
    staticUserEmail.textContent = "Please try again later.";
    staticUserIdType.textContent = "-";
    if (profilePic) {
      // Esconde a imagem se houver erro para não mostrar o placeholder quebrado
      profilePic.style.display = 'none';
    }
  }
})();