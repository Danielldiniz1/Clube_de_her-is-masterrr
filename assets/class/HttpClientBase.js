export default class HttpClientBase {
    #baseUrl;
    #defaultHeaders;

    constructor(baseUrl = '') {
        this.#baseUrl = baseUrl;
        this.#defaultHeaders = {
            'Content-Type': 'application/json'
        };
    }

    setAuthToken(token) {
    this.#defaultHeaders['Authorization'] = `Bearer ${token}`;
}


    clearAuthToken() {
    delete this.#defaultHeaders['Authorization'];
}


    #buildUrl(endpoint, params = {}) {
        let url = `${this.#baseUrl}${endpoint}`;

        const queryParams = new URLSearchParams();
        for (const [key, value] of Object.entries(params)) {
            if (url.includes(`/:${key}`)) {
                url = url.replace(`:${key}`, value);
            } else {
                queryParams.append(key, value);
            }
        }

        const queryString = queryParams.toString();
        if (queryString) {
            url += `?${queryString}`;
        }

        return url;
    }

    async #fetchWithConfig(endpoint, config, params = {}) {
        try {
            const url = this.#buildUrl(endpoint, params);
            const response = await fetch(url, {
                ...config,
                headers: {
                    ...this.#defaultHeaders,
                    ...config.headers
                }
            });

            const contentType = response.headers.get('content-type');
            const isJson = contentType && contentType.includes('application/json');

            if (!response.ok) {
                let errorData;
                try {
                    errorData = isJson ? await response.json() : { message: await response.text() };
                } catch (e) {
                    errorData = { message: `HTTP error! status: ${response.status}` };
                }
                const error = new Error(errorData.message || `HTTP error! status: ${response.status}`);
                error.response = errorData;
                error.status = response.status;
                throw error;
            }

            if (isJson) {
                return await response.json();
            }

            return await response.text();

        } catch (error) {
            throw error; // Re-throw the enriched error object
        }
    }

    async get(endpoint, params = {}) {
        return this.#fetchWithConfig(endpoint, {
            method: 'GET'
        }, params);
    }

    async post(endpoint, data = null, params = {}) {
        const config = {
            method: 'POST',
            body: data instanceof FormData ? data : JSON.stringify(data)
        };

        if (data instanceof FormData) {
            delete this.#defaultHeaders['Content-Type'];
        }

        return this.#fetchWithConfig(endpoint, config, params);
    }

    async put(endpoint, data = null, params = {}) {
        let config = {
            method: 'PUT',
            headers: {}
        };

        if (data instanceof FormData) {
            config.body = new URLSearchParams(data).toString();
            config.headers['Content-Type'] = 'application/x-www-form-urlencoded';
        } else {
            config.body = JSON.stringify(data);
            config.headers['Content-Type'] = 'application/json';
        }

        return this.#fetchWithConfig(endpoint, config, params);
    }

    async delete(endpoint, params = {}) {
        return this.#fetchWithConfig(endpoint, {
            method: 'DELETE'
        }, params);
    }
}