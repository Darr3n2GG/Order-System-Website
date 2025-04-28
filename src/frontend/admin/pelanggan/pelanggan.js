import FetchHelper from "../../../scripts/FetchHelper.js";
import { FileInput } from "../../../scripts/FileInput.js";

let files_received = null;
globalThis.logFiles = () => {
    return files_received;
};

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI.php";


const formPelanggan = document.querySelector(".form_pelanggan");

formPelanggan.addEventListener("submit", (event) => {
    event.preventDefault();
    if (files_received != null) {
        postCSVFile();
    } else if (validateForm()) {
        const data = new FormData(formPelanggan);
        postPelangganData(data, "Pelanggan ditambah.");
    }
});

function postCSVFile() {
    const data = new FormData;
    for (const file of files_received) {
        data.append("files[]", file)
    }

    postPelangganData(data, "File CSV dihantar.")
}

async function postPelangganData(formData, message) {
    try {
        const response = await fetch(ApiUrl, {
            method: "POST",
            body: formData
        })

        const responseMessage = await FetchHelper.onFulfilled(response)
        if (responseMessage.ok) {
            alert(message);
            setTimeout(location.reload(), 500)
        } else {
            console.error(responseMessage.message);
        }
    } catch (error) {
        FetchHelper.onRejected(error);
    }
}


formPelanggan.addEventListener("input", (event) => {
    const id = event.target.id;
    validateField(id);
})


const formValidity = {
    tambah_nama: { condition: (value) => handleNamaValidation(value) },
    tambah_no_phone: { condition: (value) => handleNoPasswordValidation(value) },
    tambah_password: { condition: (value) => handlePasswordValidation(value) },
    tambah_tahap: { condition: (value) => handleTahapValidation(value) }
};

function validateField(fieldId) {
    const { condition } = formValidity[fieldId];
    const field = document.getElementById(fieldId);

    const message = condition(field.value.trim())
    if (message === "") {
        field.setCustomValidity("");
        return true;
    } else {
        field.setCustomValidity(message);
        field.reportValidity();
        return false;
    }
}

function validateForm() {
    for (const fieldId in formValidity) {
        if (validateField(fieldId) === false) { return false; }
    }
    return true;
}

function handleNamaValidation(value) {
    if (value === "") {
        return "Field nama kosong.";
    } else if (!isValidCharacters(value)) {
        return "Field nama terdapat character invalid.";
    } else if (value.length >= 100) {
        return "Field nama mesti kurang daripada 100.";
    } else {
        return "";
    }
}

function handlePasswordValidation(value) {
    if (value === "") {
        return "Field password kosong.";
    } else if (!isValidCharacters(value)) {
        return "Field password terdapat character invalid.";
    } else if (value.length <= 8 || value.length >= 128) {
        return "Field password mesti melebihi 8 dan kurang daripada 128.";
    } else {
        return "";
    }
}

function handleNoPasswordValidation(value) {
    if (value === "") {
        return "Field nombor phone kosong.";
    } else if (!isValidPhoneNumber(value)) {
        return "Field password invalid.";
    } else {
        return "";
    }
}

function handleTahapValidation(value) {
    if (value === "") {
        return "Field tahap kosong.";
    } else {
        return "";
    }
}

function isValidCharacters(value) {
    const whitelistPattern = /^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?`~]+$/;
    return whitelistPattern.test(value);
}

function isValidPhoneNumber(value) {
    const whitelistPattern = /^(\+?6?01)[02-46-9]-*[0-9]{7}$|^(\+?6?01)[1]-*[0-9]{8}$/;
    return whitelistPattern.test(value);
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
    editDialog.hide()
})