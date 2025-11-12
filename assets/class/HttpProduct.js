import HttpClientBase from './HttpClientBase.js';

export default class HttpProduct extends HttpClientBase {
  constructor(baseUrl = null) {
    const APP_BASE = typeof window.__APP_BASE === 'string' ? window.__APP_BASE : `${window.location.origin}`;
    super(baseUrl || `${APP_BASE}/api/products`);
  }

  async list() {
    return this.get('/');
  }

  async getById(id) {
    return this.get('/product/:id', { id });
  }

  async create(formData) {
    return this.post('/', formData);
  }

  async update(id, formData) {
    return this.put('/product/:id', formData, { id });
  }

  async delete(id) {
    return this.delete('/product/:id', { id });
  }
}