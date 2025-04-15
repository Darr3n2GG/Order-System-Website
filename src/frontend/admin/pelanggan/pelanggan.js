import { FileInput } from "../../../scripts/FileInput.js";

let files_received;
globalThis.logFiles = () => {
    return files_received;
};

const CSVInput = document.querySelector(".csv_input");
const filesList = document.querySelector(".files_list")

const fileInput = new FileInput(true, ".csv");

CSVInput.addEventListener("click", () => {
    fileInput.clickInput();
    filesList.innerHTML = "";
})

fileInput.getInput().addEventListener("change", ({ target }) => {
    console.log("files added : ");

    files_received = target.files
    for (const file of files_received) {
        filesList.insertAdjacentHTML("beforeend",
            `<li>${file.name}</li>`
        );
    }
})



