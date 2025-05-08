import BaseModel from "../shared/BaseModel.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI2.php";

class PelangganModel extends BaseModel {
    constructor() {
        super(ApiUrl);  // Call the constructor of the base class with the API URL
    }
}

const pelModel = new PelangganModel();
export default pelModel;
