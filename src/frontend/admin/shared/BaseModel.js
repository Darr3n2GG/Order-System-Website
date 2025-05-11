import FetchHelper from "../../../scripts/FetchHelper.js";

class BaseModel {
    constructor(apiUrl) {
        this.apiUrl = apiUrl;
    }

    getApiUrl() {
        return this.apiUrl;
    }

    async get(params = {}) {
        try {
            const url = new URL(this.apiUrl, window.location.origin);
            if (Object.keys(params).length > 0) {
                url.search = new URLSearchParams(params).toString();
            }

            const response = await fetch(url);
            return await FetchHelper.onFulfilled(response);
        } catch (error) {
            console.error(`GET request failed for ${this.apiUrl}:`, error);
            return { ok: false, error };
        }
    }

    async insert(data) {
        // for (const [key, value] of data.entries()) {
        //     console.log(`${key}:`, value);
        // }
        try {
            const response = await fetch(this.apiUrl, {
                method: "POST",
                body: data
            });
            return await FetchHelper.onFulfilled(response);
        } catch (error) {
            console.error(`INSERT request failed for ${this.apiUrl}:`, error);
            return { ok: false, error };
        }
    }

    async update(formData) {
        try {
            const plainData = Object.fromEntries(formData.entries());
            const response = await fetch(this.apiUrl, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(plainData)
            });
            return await FetchHelper.onFulfilled(response);
        } catch (error) {
            console.error(`UPDATE request failed for ${this.apiUrl}:`, error);
            return { ok: false, error };
        }
    }

    async delete(id) {
        try {
            const url = `${this.apiUrl}?id=${encodeURIComponent(id)}`;
            const response = await fetch(url, { method: "DELETE" });
            return await FetchHelper.onFulfilled(response);
        } catch (error) {
            console.error(`DELETE request failed for ${this.apiUrl}:`, error);
            return { ok: false, error };
        }
    }
}

export default BaseModel;
