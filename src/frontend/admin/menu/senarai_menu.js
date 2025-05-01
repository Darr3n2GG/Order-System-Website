import FetchHelper from "../../../scripts/FetchHelper.js";
import { FileInput } from "../../../scripts/FileInput.js";
import FormValidator from "../../../scripts/FormValidator.js";

let files_received = null;
globalThis.logFiles = () => {
    return files_received;
};

const ApiUrl = "/Order-System-Website/src/backend/api/ProdukAPI.php";


const formProduk = document.querySelector(".form_produk");

formProduk.addEventListener("submit", (event) => {
    event.preventDefault();
    if (files_received != null) {
        postCSVFile();
    } else {
        const data = new FormData(formProduk);
        postProdukData(data, "Produk ditambah.");
    }
});

async function postProdukData(formData, message) {
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