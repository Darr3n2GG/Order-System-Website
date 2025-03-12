import { eventBus } from "../../scripts/EventBus.js";
import FetchHelper from "../../scripts/FetchHelper.js";

const apiUrl = "/Order-System-Website/src/backend/MakananAPI.php"
let selectedItem = {};
// selectedItem logger in console, type "logSelectedItem()"
globalThis.logSelectedItem = () => selectedItem;

const itemDialog = document.querySelector(".item_dialog");
const dialogAmount = itemDialog.querySelector(".dialog_input");

document.addEventListener("DOMContentLoaded", () => {
    setupItemDialog();
})

dialogAmount.addEventListener("sl-change", () => {
    updateItemQuantity(parseInt(dialogAmount.value, 10));
});

itemDialog.querySelector(".add_item_button").addEventListener("click", () =>{
    addSelectedItemToCart();
    itemDialog.hide();
});

// Delay reset for event to be emitted
itemDialog.addEventListener("sl-after-hide", () => {
    resetItemDialog();
});


function setupItemDialog() {
    const menu = document.querySelector(".menu");
    menu.addEventListener("click", ({ target }) => {
        if (target.classList.contains("food_item")) {
            handleOnItemClick(target);
        }
    })
}

function handleOnItemClick(target) {
    if (detectIfNoTextSelected() === true) {
        const itemID = parseInt(target.dataset.id, 10);
        fetchItemDialogData(itemID);
    }
}

function detectIfNoTextSelected() {
    const noTextSelected = !window.getSelection().toString();
    return noTextSelected;
}

function fetchItemDialogData(item_id) {
    const url = apiUrl + "?" + new URLSearchParams({
        id : item_id
    }).toString();
    fetch(url)
        .then(FetchHelper.onFulfilled)
        .then(({ details }) => {
            setSelectedItem(details.item);
            showItemDialog(details.item);
        })
        .catch(FetchHelper.onRejected);
}

function showItemDialog({label, id, nama, gambar, harga, detail}) {
    itemDialog.label = label + id +  " : " + nama;
    itemDialog.querySelector(".dialog_image").src = gambar;
    itemDialog.querySelector(".dialog_price").innerHTML = "Harga : RM" + harga;
    itemDialog.querySelector(".dialog_description").innerHTML = detail;
    itemDialog.show();
}

function setSelectedItem(item) {
    selectedItem = item;
    selectedItem.kuantiti = 1;
}

function addSelectedItemToCart() {
    eventBus.emit("addItemToCart", {
        item : selectedItem
    });
}

function updateItemQuantity(value) {
    selectedItem.kuantiti = value;
}

function resetItemDialog() {
    selectedItem = {};
    dialogAmount.value = 1;
}

