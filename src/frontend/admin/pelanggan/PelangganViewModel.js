import BaseViewModel from "../shared/BaseViewModel.js";
import PelangganModel from "./PelangganModel.js";

const VALID_CHAR_REGEX = /^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?`~]+$/;
const PHONE_REGEX = /^(\+?6?01)[02-46-9]-*\d{7}$|^(\+?6?01)[1]-*\d{8}$/;

class PelangganViewModel extends BaseViewModel {
    constructor() {
        super(PelangganModel);
        this.filters = {
            nama: "",
            no_phone: ""
        };

        this.validators = {
            nama: (value) => {
                const trimmed = value?.trim() ?? "";
                if (!trimmed) return "Field nama kosong.";
                if (!VALID_CHAR_REGEX.test(trimmed)) return "Field nama mengandungi karakter tidak sah.";
                if (trimmed.length >= 100) return "Field nama mesti kurang daripada 100 karakter.";
                return "";
            },
            no_phone: (value) => {
                const trimmed = value?.trim() ?? "";
                if (!trimmed) return "Field nombor telefon kosong.";
                if (!PHONE_REGEX.test(trimmed)) return "Field nombor telefon tidak sah.";
                return "";
            },
            tahap: (value) => (!value ? "Field tahap kosong." : ""),
            password: (value) => {
                const trimmed = value?.trim() ?? "";
                if (!trimmed) return "Field kata laluan kosong.";
                if (!VALID_CHAR_REGEX.test(trimmed)) return "Field kata laluan mengandungi karakter tidak sah.";
                if (trimmed.length <= 8 || trimmed.length >= 128)
                    return "Field kata laluan mesti melebihi 8 dan kurang daripada 128 karakter.";
                return "";
            }
        };
    }

    isValidCharacters(value) {
        return VALID_CHAR_REGEX.test(value);
    }
}

export default new PelangganViewModel();
