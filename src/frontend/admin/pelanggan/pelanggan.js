import FetchHelper from "../../../scripts/FetchHelper.js";
import { FileInput } from "../../../scripts/FileInput.js";

let files_received = null;
globalThis.logFiles = () => {
    return files_received;
};

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI.php";


const pelangganForm = document.querySelector(".pelanggan_form");

pelangganForm.addEventListener("submit", (_event) => {
    if (files_received != null) {
        postCSVFile();
    } else if (validateForm()) {
        // submit
        return;
    }
});

async function postCSVFile() {
    const data = new FormData();
    for (const file of files_received) {
        data.append("files[]", file)
    }

    try {
        const response = await fetch(ApiUrl, {
            method: "POST",
            body: data
        })

        const message = await FetchHelper.onFulfilled(response)
        if (message.ok) {
            alert("Fail CSV dihantar.");
            setTimeout(location.reload(), 500)
        } else {
            console.error(message.message);
        }
    } catch (error) {
        FetchHelper.onRejected(error);
    }
}


pelangganForm.addEventListener("input", (event) => {
    const id = event.target.id;
    validateField(id);
})


const formValidity = {
    nama: { condition: (value) => handleNamaValidation(value) },
    no_phone: { condition: (value) => { } },
    password: { condition: (value) => handlePasswordValidation(value) }
}

function validateField(fieldId) {
    const { condition } = formValidity[fieldId];
    const field = document.getElementById(fieldId);

    const message = condition(field.value.trim())
    if (message === "") {
        field.setCustomValidity("");
    } else {
        field.setCustomValidity(message);
        field.reportValidity();
        return false;
    }
}

function validateForm() {
    for (const fieldId in formValidity) {
        if (validateField(fieldId) === false) { return false }
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
    }

    return "";
}

function handlePasswordValidation(value) {
    if (value === "") {
        return "Field password kosong.";
    } else if (!isValidCharacters(value)) {
        return "Field password terdapat character invalid.";
    } else if (value.length <= 8 || value.length >= 128) {
        return "Field password mesti melebihi 8 dan kurang daripada 128.";
    }

    return "";
}

function isValidCharacters(value) {
    const whitelistPattern = /^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?`~]+$/;
    return whitelistPattern.test(value);
}


const CSVInput = document.querySelector(".csv_input");
const filesList = document.querySelector(".files_list")

const fileInput = new FileInput(true, ".csv");

CSVInput.addEventListener("click", () => {
    fileInput.clickInput();
    filesList.innerHTML = "";
    files_received = null;
})

fileInput.getInput().addEventListener("change", ({ target }) => {
    files_received = target.files;
    for (const file of files_received) {
        filesList.insertAdjacentHTML("beforeend",
            `<li>${file.name}</li>`
        );
    }
})