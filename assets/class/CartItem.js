export default class CartItem {
  #product_id; #quantity; #name; #price; #image_path;

  constructor({ product_id = null, quantity = 1, name = null, price = 0, image_path = null } = {}) {
    this.#product_id = product_id;
    this.#quantity = quantity;
    this.#name = name;
    this.#price = price;
    this.#image_path = image_path;
  }

  getProductId() { return this.#product_id; }
  getQuantity() { return this.#quantity; }
  getName() { return this.#name; }
  getPrice() { return this.#price; }
  getImagePath() { return this.#image_path; }

  setProductId(v) { this.#product_id = v; }
  setQuantity(v) { this.#quantity = Math.max(1, parseInt(v ?? 1, 10)); }
  setName(v) { this.#name = v; }
  setPrice(v) { this.#price = parseFloat(v ?? 0); }
  setImagePath(v) { this.#image_path = v; }

  toAddData() {
    return { product_id: this.#product_id, quantity: this.#quantity };
  }

  toUpdateData() {
    return { product_id: this.#product_id, quantity: this.#quantity };
  }

  static fromApi(obj = {}) {
    return new CartItem({
      product_id: obj.product_id ?? null,
      quantity: obj.quantity ?? 1,
      name: obj.name ?? null,
      price: obj.price ?? 0,
      image_path: obj.image_path ?? null
    });
  }
}