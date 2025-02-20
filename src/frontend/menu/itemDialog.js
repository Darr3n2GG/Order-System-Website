import { eventBus } from "../../scripts/eventBus.js";
import fetchHelper from "../../scripts/fetchHelper.js";

let selectedItem = {};
globalThis.selectedItem = selectedItem; //Console accessor

const itemDialog = document.querySelector(".item_dialog");
const dialogAmount = itemDialog.querySelector(".dialog_input");
const dialogButton = itemDialog.querySelector(".add_item_button")

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
    selectedItem = item;
    selectedItem.kuantiti = 1
}

function showItemDialog(item) {
    itemDialog.label = item.label + item.id +  " : " + item.nama;
    itemDialog.querySelector(".dialog_image").src = item.gambar;
    itemDialog.querySelector(".dialog_price").innerHTML = "Harga : RM" + item.harga;
    itemDialog.show();
}

dialogAmount.addEventListener("sl-change", () => {
    updateItemQuantity(parseInt(dialogAmount.value));
});

function updateItemQuantity(value) {
    selectedItem.kuantiti = value
}

dialogButton.addEventListener("click", () =>{
    eventBus.emit("addItemToCart", {
        item : selectedItem
    });
    dialog.hide();
});

itemDialog.addEventListener("sl-after-hide", () => {
    selectedItem = {};
});