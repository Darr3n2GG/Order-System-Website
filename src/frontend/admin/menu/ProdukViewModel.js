import BaseViewModel from "../shared/BaseViewModel.js";
import produkModel from "./ProdukModel.js";

class ProdukViewModel extends BaseViewModel {
    constructor() {
        super(produkModel);
        this.resourceName = "Produk";
        this.validators = {
            // whatever defined in formValidity must define here
            // database field => message
            nama: (value) => (!value ? "Field nama kosong." : ""),
            id_kategori: (value) => (!value ? "Field kategori kosong." : ""),
            maklumat: (value) => (!value ? "Field maklumat kosong." : ""),
            harga: (value) => (!value ? "Field harga kosong." : ""),
            gambar: (value) => (!value ? "Field gambar kosong." : "")
        };
    }

    // Custom functions
    async uploadPhoto(data) {
        const result = await this.model.uploadPhoto(data);
        return result.details || [];
    }
}

export default new ProdukViewModel();
