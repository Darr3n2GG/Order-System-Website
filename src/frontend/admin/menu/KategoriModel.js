import BaseModel from "../shared/BaseModel.js";
import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/KategoriAPI2.php";

class KategoriModel extends BaseModel {
    constructor() {
        super(ApiUrl);  // Call the constructor of the base class with the API URL
    }
}

const kategoriModel = new KategoriModel();
export default kategoriModel;
