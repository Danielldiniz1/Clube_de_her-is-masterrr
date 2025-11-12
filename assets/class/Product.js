export class Product {
  #id;
  #club_id;
  #name;
  #description;
  #price;
  #stock;
  #category_id;
  #fandom;
  #rarity;
  #is_physical;
  #subscription_only;
  #weight_grams;
  #dimensions_cm;
  #is_active;

  constructor({
    id = null,
    club_id = null,
    name = '',
    description = '',
    price = 0,
    stock = 0,
    category_id = null,
    fandom = '',
    rarity = 'common',
    is_physical = true,
    subscription_only = false,
    weight_grams = null,
    dimensions_cm = '',
    is_active = true
  } = {}) {
    this.#id = id;
    this.#club_id = club_id;
    this.#name = name;
    this.#description = description;
    this.#price = price;
    this.#stock = stock;
    this.#category_id = category_id;
    this.#fandom = fandom;
    this.#rarity = rarity;
    this.#is_physical = !!is_physical;
    this.#subscription_only = !!subscription_only;
    this.#weight_grams = weight_grams;
    this.#dimensions_cm = dimensions_cm;
    this.#is_active = !!is_active;
  }

  // Getters
  getId() { return this.#id; }
  getClubId() { return this.#club_id; }
  getName() { return this.#name; }
  getDescription() { return this.#description; }
  getPrice() { return this.#price; }
  getStock() { return this.#stock; }
  getCategoryId() { return this.#category_id; }
  getFandom() { return this.#fandom; }
  getRarity() { return this.#rarity; }
  getIsPhysical() { return this.#is_physical; }
  getSubscriptionOnly() { return this.#subscription_only; }
  getWeightGrams() { return this.#weight_grams; }
  getDimensionsCm() { return this.#dimensions_cm; }
  getIsActive() { return this.#is_active; }

  // Setters
  setId(v) { this.#id = v; }
  setClubId(v) { this.#club_id = v; }
  setName(v) { this.#name = v; }
  setDescription(v) { this.#description = v; }
  setPrice(v) { this.#price = v; }
  setStock(v) { this.#stock = v; }
  setCategoryId(v) { this.#category_id = v; }
  setFandom(v) { this.#fandom = v; }
  setRarity(v) { this.#rarity = v; }
  setIsPhysical(v) { this.#is_physical = !!v; }
  setSubscriptionOnly(v) { this.#subscription_only = !!v; }
  setWeightGrams(v) { this.#weight_grams = v; }
  setDimensionsCm(v) { this.#dimensions_cm = v; }
  setIsActive(v) { this.#is_active = !!v; }

  // Convert to FormData for create/update
  toFormData(extraFiles = []) {
    const fd = new FormData();
    if (this.#id) fd.append('id', String(this.#id));
    if (this.#club_id) fd.append('club_id', String(this.#club_id));
    fd.append('name', this.#name);
    if (this.#description) fd.append('description', this.#description);
    fd.append('price', String(this.#price));
    fd.append('stock', String(this.#stock ?? 0));
    if (this.#category_id) fd.append('category_id', String(this.#category_id));
    if (this.#fandom) fd.append('fandom', this.#fandom);
    if (this.#rarity) fd.append('rarity', this.#rarity);
    if (this.#weight_grams) fd.append('weight_grams', String(this.#weight_grams));
    if (this.#dimensions_cm) fd.append('dimensions_cm', this.#dimensions_cm);
    fd.append('is_physical', this.#is_physical ? '1' : '0');
    fd.append('subscription_only', this.#subscription_only ? '1' : '0');
    fd.append('is_active', this.#is_active ? '1' : '0');

    // Images
    if (Array.isArray(extraFiles)) {
      for (const file of extraFiles) {
        fd.append('images[]', file);
      }
    }
    return fd;
  }

  static fromApi(obj = {}) {
    return new Product({
      id: obj.id ?? null,
      club_id: obj.club_id ?? null,
      name: obj.name ?? '',
      description: obj.description ?? '',
      price: obj.price ?? 0,
      stock: obj.stock ?? 0,
      category_id: obj.category_id ?? null,
      fandom: obj.fandom ?? '',
      rarity: obj.rarity ?? 'common',
      is_physical: obj.is_physical ?? true,
      subscription_only: obj.subscription_only ?? false,
      weight_grams: obj.weight_grams ?? null,
      dimensions_cm: obj.dimensions_cm ?? '',
      is_active: obj.is_active ?? true
    });
  }
}