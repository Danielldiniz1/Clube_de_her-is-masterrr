export default class Club {
  #id; #user_id; #club_name; #description; #is_active; #created_at;

  constructor({ id = null, user_id = null, club_name = null, description = null, is_active = true, created_at = null } = {}) {
    this.#id = id;
    this.#user_id = user_id;
    this.#club_name = club_name;
    this.#description = description;
    this.#is_active = is_active;
    this.#created_at = created_at;
  }

  // Getters
  getId() { return this.#id; }
  getUserId() { return this.#user_id; }
  getClubName() { return this.#club_name; }
  getDescription() { return this.#description; }
  getIsActive() { return this.#is_active; }
  getCreatedAt() { return this.#created_at; }

  // Setters
  setId(v) { this.#id = v; }
  setUserId(v) { this.#user_id = v; }
  setClubName(v) { this.#club_name = v; }
  setDescription(v) { this.#description = v; }
  setIsActive(v) { this.#is_active = !!v; }
  setCreatedAt(v) { this.#created_at = v; }

  toFormData() {
    const fd = new FormData();
    if (this.#user_id != null) fd.append('user_id', this.#user_id);
    if (this.#club_name != null) fd.append('club_name', this.#club_name);
    if (this.#description != null) fd.append('description', this.#description);
    fd.append('is_active', this.#is_active ? '1' : '0');
    return fd;
  }

  static fromApi(obj = {}) {
    return new Club({
      id: obj.id ?? null,
      user_id: obj.user_id ?? null,
      club_name: obj.club_name ?? obj.name ?? null,
      description: obj.description ?? null,
      is_active: obj.is_active ?? true,
      created_at: obj.created_at ?? null
    });
  }
}