import HttpClientBase from './HttpClientBase.js';

export default class HttpUser extends HttpClientBase {
  constructor(baseUrl = null) {
    const APP_BASE = typeof window.__APP_BASE === 'string' ? window.__APP_BASE : `${window.location.origin}`;
    super(baseUrl || `${APP_BASE}/api/users`);
  }

  async login(formData) {
    return this.post('/login', formData);
  }

  async register(data) {
    // Supports FormData or plain object
    return this.post('/register', data);
  }

  async updateProfile(data) {
    // Requires auth header set via setAuthToken
    return this.post('/profile', data);
  }

  async updateById(id, data) {
    return this.put(`/${id}`, data);
  }

  async getById(id) {
    return this.get(`/${id}`);
  }
}