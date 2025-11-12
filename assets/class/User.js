export class User {
  #id;
  #name;
  #email;
  #password;
  #photo;
  #idType;

  constructor(id = null, name = '', email = '', password = '', photo = null, idType = 2) {
    this.#id = id;
    this.#name = name;
    this.#email = email;
    this.#password = password;
    this.#photo = photo;
    this.#idType = idType;
  }

  // Getters
  getId() { return this.#id; }
  getName() { return this.#name; }
  getEmail() { return this.#email; }
  getPassword() { return this.#password; }
  getPhoto() { return this.#photo; }
  getIdType() { return this.#idType; }

  // Setters
  setId(id) { this.#id = id; }
  setName(name) { this.#name = name; }
  setEmail(email) { this.#email = email; }
  setPassword(password) { this.#password = password; }
  setPhoto(photo) { this.#photo = photo; }
  setIdType(idType) { this.#idType = idType; }

  // Helper: popular formulário de perfil
  formLoad(form) {
    if (!form) return;
    if (form.name) form.name.value = this.#name || '';
    if (form.email) form.email.value = this.#email || '';
  }

  // Serializa para envio de cadastro
  toRegisterFormData() {
    const fd = new FormData();
    fd.append('name', this.#name);
    fd.append('email', this.#email);
    fd.append('password', this.#password);
    fd.append('idType', String(this.#idType));
    if (this.#photo) fd.append('photo', this.#photo);
    return fd;
  }

  // Atualização de perfil (sem senha)
  toProfileData() {
    return {
      name: this.#name,
      email: this.#email,
      idType: this.#idType
    };
  }

  static fromApi(userObj = {}) {
    return new User(
      userObj.id ?? null,
      userObj.name ?? '',
      userObj.email ?? '',
      '',
      userObj.photo ?? null,
      userObj.idType ?? 2
    );
  }
}