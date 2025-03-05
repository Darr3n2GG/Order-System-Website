import { eventBus } from "../../scripts/eventBus.js";
import fetchHelper from "../../scripts/fetchHelper.js";

let selectedItem = {};
// selectedItem logger in console, type "logSelectedItem()"
globalThis.logSelectedItem = (function(){
    return selectedItem;
});

const itemDialog = document.querySelector(".item_dialog");
const dialogAmount = itemDialog.querySelector(".dialog_input");
const addItemButton = itemDialog.querySelector(".add_item_button");

document.addEventListener("DOMContentLoaded", () => {
    setupItemDialog();
})

dialogAmount.addEventListener("sl-change", () => {
    updateItemQuantity(parseInt(dialogAmount.value));
});

addItemButton.addEventListener("click", () =>{
    addSelectedItemToCart();
    itemDialog.hide();
});

// Delay reset for event to be emitted
itemDialog.addEventListener("sl-after-hide", () => {
    resetItemDialog();
});


function setupItemDialog() {
    const menu = document.querySelector(".menu");
    menu.addEventListener("click", event => {
        if (event.target.classList.contains("food_item")) {
            handleOnItemClick(event);
        }
    })
}

function handleOnItemClick(event) {
    if (detectIfNoTextSelected() === true) {
        const itemID = event.target.dataset.id;
        fetchItemDialogData(itemID);
    }
}

function detectIfNoTextSelected() {
    const noTextSelected = !window.getSelection().toString();
    return noTextSelected;
}

function fetchItemDialogData(item_id) {
    const url = "/Order-System-Website/src/backend/MakananAPI.php?" + new URLSearchParams({
        id : item_id
    }).toString();
    fetch(url)
        .then(fetchHelper.onFulfilled)
        .then(item => setItemAndShowDialog(item))
        .catch(fetchHelper.onRejected);
}

function setItemAndShowDialog(item) {
    setSelectedItem(item);
    showItemDialog(item);
}

function setSelectedItem(item) {
    selectedItem = item;
    selectedItem.kuantiti = 1;
}

function showItemDialog(item) {
    itemDialog.label = item.label + item.id +  " : " + item.nama;
    itemDialog.querySelector(".dialog_image").src = item.gambar;
    itemDialog.querySelector(".dialog_price").innerHTML = "Harga : RM" + item.harga;
    itemDialog.querySelector(".dialog_description").innerHTML = item.detail;
    itemDialog.show();
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

