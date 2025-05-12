import BaseViewModel from "../shared/BaseViewModel.js";
import kategoriModel from "./KategoriModel.js";

class KategoriViewModel extends BaseViewModel {
    constructor() {
        super(kategoriModel);
        this.resourceName = "Kategori";
        this.validators = {
            // database field => message
            label: (value) => (!value ? "Field label kosong." : ""),
            nama: (value) => (!value ? "Field nama kosong." : ""),
        };
    }
}

export default new KategoriViewModel();
