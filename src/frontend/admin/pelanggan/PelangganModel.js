import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI2.php";

const PelangganModel = {
    async get(params = {}) {
        try {
            const url = new URL(ApiUrl, window.location.origin);
            if (Object.keys(params).length > 0) {
                url.search = new URLSearchParams(params).toString();
            }

            const response = await fetch(url);
            return await FetchHelper.onFulfilled(response);
        } catch (error) {
            console.error("GET Pelanggan failed:", error);
            return { ok: false, error };
        }
    },

    async insert(data) {
        try {
            const response = await fetch(ApiUrl, {
                method: "POST",
                body: data
            });
            return await FetchHelper.onFulfilled(response);
        } catch (error) {
            console.error("INSERT Pelanggan failed:", error);
            return { ok: false, error };
        }
    },

    async update(formData) {
        try {
            const plainData = Object.fromEntries(formData.entries()); // safer .entries()
            const response = await fetch(ApiUrl, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(plainData)
            });
            return await FetchHelper.onFulfilled(response);
        } catch (error) {
            console.error("UPDATE Pelanggan failed:", error);
            return { ok: false, error };
        }
    },

    async delete(id) {
        try {
            const url = `${ApiUrl}?id=${encodeURIComponent(id)}`;
            const response = await fetch(url, { method: "DELETE" });
            return await FetchHelper.onFulfilled(response);
        } catch (error) {
            console.error("DELETE Pelanggan failed:", error);
            return { ok: false, error };
        }
    }
};

export default PelangganModel;
