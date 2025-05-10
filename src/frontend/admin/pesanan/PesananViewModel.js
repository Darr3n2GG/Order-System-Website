import BaseViewModel from "../shared/BaseViewModel.js";
import pesananModel from "./PesananModel.js";

class PesananViewModel extends BaseViewModel {
    constructor() {
        super(pesananModel);

        this.validators = {
            // database field => message
            id_pelanggan: (value) => (!value ? "Field pelanggan kosong." : ""),
            tarikh: (value) => (!value ? "Field tarikh kosong." : ""),
            status: (value) => (!value ? "Field status kosong." : ""),
            id_status: (value) => (!value ? "Field status kosong." : ""),
            cara: (value) => (!value ? "Field cara kosong." : ""),
            no_meja: (value) => (!value ? "Field meja kosong." : "")
        };
    }

    // Custom functions
    async getStatus() {
        const data = await this.model.getStatus();
        return data.details || [];
    }

    async getMeja() {
        const data = await this.model.getMeja();
        return data.details || [];
    }
}

export default new PesananViewModel();
