import FetchHelper from "../../../scripts/FetchHelper.js";
import { FileInput } from "../../../scripts/FileInput.js";
import FormValidator from "../../../scripts/FormValidator.js";

let files_received = null;
globalThis.logFiles = () => {
    return files_received;
};

const ApiUrl = "/Order-System-Website/src/backend/api/PesananAPI.php";

const formPesanan = document.querySelector(".form_pesanan");

formPesanan.addEventListener("submit", (event) => {
    event.preventDefault();
    if (files_received != null) {
        postCSVFile();
    } else if (FormValidator.validateForm(pesananFormValidity)) {
        const data = new FormData(formPesanan);
        postPesananData(data, "Pesanan berjaya dihantar.");
    }
});

function postCSVFile() {
    const data = new FormData();
    for (const file of files_received) {
        data.append("files[]", file);
    }
    postPesananData(data, "File CSV dihantar.");
}

async function postPesananData(formData, message) {
    try {
        const response = await fetch(ApiUrl, {
            method: "POST",
            body: formData,
        });

        const responseMessage = await FetchHelper.onFulfilled(response);
        if (responseMessage.ok) {
            alert(message);
            setTimeout(() => location.reload(), 500);
        } else {
            console.error(responseMessage.message);
        }
    } catch (error) {
        FetchHelper.onRejected(error);
    }
}

formPesanan.addEventListener("input", (event) => {
    const id = event.target.id;
    FormValidator.validateField(pesananFormValidity, id);
});

const pesananFormValidity = {
    pesanan_nama_produk: { condition: (value) => validateProduk(value) },
    pesanan_kuantiti: { condition: (value) => validateKuantiti(value) },
    pesanan_tarikh: { condition: (value) => validateTarikh(value) },
};

function validateProduk(value) {
    if (value === "") {
        return "Nama produk tidak boleh kosong.";
    } else if (value.length > 100) {
        return "Nama produk mesti kurang daripada 100 aksara.";
    } else {
        return "";
    }
}

function validateKuantiti(value) {
    const quantity = parseInt(value, 10);
    if (value === "") {
        return "Kuantiti tidak boleh kosong.";
    } else if (isNaN(quantity) || quantity <= 0) {
        return "Kuantiti mesti nombor lebih besar daripada 0.";
    } else {
        return "";
    }
}

function validateTarikh(value) {
    if (value === "") {
        return "Tarikh tidak boleh kosong.";
    } else {
        return "";
    }
}

const CSVInput = document.querySelector(".csv_input");
const filesList = document.querySelector(".files_list");

const fileInput = new FileInput(true, ".csv");

CSVInput.addEventListener("click", () => {
    fileInput.clickInput();
    filesList.innerHTML = "<p class='include_tag hide'>Files included :</p>";
    files_received = null;
});

fileInput.getInput().addEventListener("change", ({ target }) => {
    files_received = target.files;

    const includeTag = filesList.querySelector(".include_tag");
    if (files_received.length === 0) {
        includeTag.classList.add("hide");
    } else {
        includeTag.classList.remove("hide");
        for (const file of files_received) {
            filesList.insertAdjacentHTML("beforeend",
                `<li>${file.name}</li>`
            );
        }
    }
});

const editDialog = document.querySelector(".edit_dialog");

editDialog.querySelector(".cancel_button").addEventListener("click", () => {
    editDialog.hide();
});
