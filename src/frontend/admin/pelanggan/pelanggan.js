import { inputFile } from "../../../scripts/FileInput";

let file_received;

const fileInput = document.querySelector(".file_input");

fileInput.addEventListener("click", () => {
    inputFile(false, ".csv").addEventListener("file_received", ({ files }) => {
        file_received = files;
        console.log(file_received)
    });
})



