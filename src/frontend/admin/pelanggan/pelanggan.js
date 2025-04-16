import { FileInput } from "../../../scripts/FileInput.js";

let files_received = null;
globalThis.logFiles = () => {
    return files_received;
};


const pelangganForm = document.querySelector(".pelanggan_form");

pelangganForm.addEventListener("submit", (event) => {
    if (files_received != null) {
        console.log("files included so no error msg")
    } else if (!pelangganForm.checkValidity()) {
        console.log("not valid")
    }
})

// pelangganForm.addEventListener("submit", () => {
//     const noPhoneField = document.getElementById("no_phone");
//     const passwordField = document.getElementById("password");


// } else if (fileInput.getInput().files.length === 0) {
//     alert("No data input");
// }
// })

// const namaField = document.getElementById("nama");
// namaField.addEventListener("input", () => {
//     const name = namaField.value.trim();

//     if (name === "") {
//         namaField.setCustomValidity("Please enter your name.");
//         namaField.reportValidity();
//     } else if (!isValidName(name)) {
//         namaField.setCustomValidity("Please enter a name with valid characters.");
//         namaField.reportValidity();
//     } else {
//         namaField.setCustomValidity("");
//     }
// })

// function isValidName(name) {
//     const whitelistPattern = /^[A-Za-zÀ-ÖØ-öø-ÿ' -]+$/;
//     return whitelistPattern.test(name);
// }


const CSVInput = document.querySelector(".csv_input");
const filesList = document.querySelector(".files_list")

const fileInput = new FileInput(true, ".csv");

CSVInput.addEventListener("click", () => {
    fileInput.clickInput();
    filesList.innerHTML = "";
    files_received = null
})

fileInput.getInput().addEventListener("change", ({ target }) => {
    files_received = target.files
    for (const file of files_received) {
        filesList.insertAdjacentHTML("beforeend",
            `<li>${file.name}</li>`
        );
    }
})