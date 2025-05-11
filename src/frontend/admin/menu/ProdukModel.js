import BaseModel from "../shared/BaseModel.js";
import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/ProdukAPI2.php";

class ProdukModel extends BaseModel {
    constructor() {
        super(ApiUrl);  // Call the constructor of the base class with the API URL
    }
    // Custom functions
    async uploadPhoto(data) {
        try {
            const response = await fetch(this.apiUrl + "/upload-image", {
                method: "POST",
                body: data
            });
            return await FetchHelper.onFulfilled(response);
        } catch (error) {
            console.error(`INSERT request failed for ${this.apiUrl + "/upload-image"}:`, error);
            return { ok: false, error };
        }
    }
}

const produkModel = new ProdukModel();
export default produkModel;
