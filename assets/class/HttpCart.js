import HttpClientBase from './HttpClientBase.js';

export default class HttpCart extends HttpClientBase {
  constructor(baseUrl = null) {
    const APP_BASE = typeof window.__APP_BASE === 'string' ? window.__APP_BASE : `${window.location.origin}`;
    super(baseUrl || `${APP_BASE}/api/cart`);
    const token = typeof localStorage !== 'undefined' ? localStorage.getItem('token') : null;
    if (token) this.setAuthToken(token);
  }

  async listItems() {
    return this.get('/items');
  }

  async add(productId, quantity = 1) {
    return this.post('/add', { product_id: productId, quantity });
  }

  async updateItem(productId, quantity) {
    return this.put('/item/:productId', { product_id: productId, quantity }, { productId });
  }

  async removeItem(productId) {
    return this.delete('/item/:productId', { productId });
  }

  async clear() {
    return this.delete('/clear');
  }
}