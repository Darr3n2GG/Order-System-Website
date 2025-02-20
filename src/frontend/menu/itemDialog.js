import { eventBus } from "../../scripts/eventBus.js";
import fetchHelper from "../../scripts/fetchHelper.js";

globalThis.selectedItem = {};

const dialogAmount = document.querySelector(".dialog_input");
const itemDialog = document.querySelector(".item_dialog");

eventBus.addEventListener("setupItemDialog", () => {
    setupItemDialog();
});

function setupItemDialog() {
    const dialogButton = document.getElementsByClassName("dialog_button");
    for (let i = 0; i < dialogButton.length; i++) {
        dialogButton[i].addEventListener("click", () => {
            const itemID = dialogButton[i].id;
            fetchItemDialogData(itemID);
        });
    }
}

function fetchItemDialogData(item_id) {
    const url = "/Order-System-Website/src/backend/fetchMakanan.php?" + new URLSearchParams({
        id : item_id
    }).toString();
    fetch(url)
        .then(fetchHelper.onFulfilled, fetchHelper.onRejected)
        .then(item => setItemAndShowDialog(item));
}

function setItemAndShowDialog(item) {
    setSelectedItem(item);
    showItemDialog(item);
}

function setSelectedItem(item) {
    globalThis.selectedItem = item;
    globalThis.selectedItem.kuantiti = 1
}

function showItemDialog(item) {
    const dialog = document.querySelector(".item_dialog");
    dialog.label = item.label + item.id +  " : " + item.nama;
    dialog.querySelector(".dialog_image").src = item.gambar;
    dialog.querySelector(".dialog_price").innerHTML = "Harga : RM" + item.harga;
    dialog.show();
}

dialogAmount.addEventListener("sl-change", () => {
    updateItemQuantity(parseInt(dialogAmount.value));
});

function updateItemQuantity(value) {
    globalThis.selectedItem.kuantiti = value
}

itemDialog.addEventListener("sl-after-hide", () => {
    globalThis.selectedItem = {};
});
