import BaseModel from "../shared/BaseModel.js";
import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PesananAPI.php";

class PesananModel extends BaseModel {
    constructor() {
        super(ApiUrl);  // Call the constructor of the base class with the API URL
    }

    // Custom functions
    async getStatus() {
        try {
            const url = new URL(this.apiUrl, window.location.origin);
            url.searchParams.set('action', 'status'); // Add the query param

            const response = await fetch(url);
            return await FetchHelper.onFulfilled(response);
        } catch (error) {
            console.error(`GET request failed for ${this.apiUrl}:`, error);
            return { ok: false, error };
        }
    }

    async getMeja() {
        try {
            const url = new URL(this.apiUrl, window.location.origin);
            url.searchParams.set('action', 'meja'); // Add the query param

            const response = await fetch(url);
            return await FetchHelper.onFulfilled(response);
        } catch (error) {
            console.error(`GET request failed for ${this.apiUrl}:`, error);
            return { ok: false, error };
        }
    }
}

const pesananModel = new PesananModel();
export default pesananModel;
