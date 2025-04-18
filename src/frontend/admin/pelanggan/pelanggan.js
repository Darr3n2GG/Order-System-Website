import FetchHelper from "../../../scripts/FetchHelper.js";
import { FileInput } from "../../../scripts/FileInput.js";

let files_received = null;
globalThis.logFiles = () => {
    return files_received;
};

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI.php";


const pelangganForm = document.querySelector(".pelanggan_form");

pelangganForm.addEventListener("submit", (event) => {
    if (validateForm()) {
        // submit
    } else if (files_received != null) {
        postCSVFile();
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

        if (response.ok) {
            alert("Fail CSV dihantar.");
        }
    } catch (error) {
        FetchHelper.onRejected(error);
    }
}


pelangganForm.addEventListener("input", () => {
    validateForm();
})


const formValidity = {
    nama: { condition: (value) => handleNamaInputValidation(value) },
    password: { condition: (value) => { } },
    no_phone: { condition: (value) => { } }
}

function validateForm() {
    for (const fieldId in formValidity) {
        const { condition } = formValidity[fieldId];
        const field = document.getElementById(fieldId);

        const message = condition(field.value.trim())
        if (message !== "") {
            field.setCustomValidity(message);
            field.reportValidity();
            return false;
        }
    }
    return true;
}

function handleNamaInputValidation(value) {
    if (value === "") {
        return "Field nama kosong.";
    } else if (!isNamaMatchWhitelist(value)) {
        return "Field nama terdapat character invalid.";
    } else {
        return "";
    }

    function isNamaMatchWhitelist(name) {
        const whitelistPattern = /^[A-Za-zÀ-ÖØ-öø-ÿ' -]+$/;
        return whitelistPattern.test(name);
    }
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