import { eventBus } from "../../scripts/EventBus.js";
import FetchHelper from "../../scripts/FetchHelper.js";

const apiUrl = "/Order-System-Website/src/backend/api/ProdukAPI.php"
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

itemDialog.querySelector(".add_item_button").addEventListener("click", () => {
    addSelectedItemToCart();
    itemDialog.hide();
});

itemDialog.addEventListener("sl-after-hide", () => {
    resetItemDialog();
});


function setupItemDialog() {
    const menu = document.querySelector(".menu");
    menu.addEventListener("click", (event) => {
        if (event.target.classList.contains("food_item")) {
            handleOnItemClick(event.target);
        } else if (event.target.closest(".food_item")) {
            event.stopPropagation();
            handleOnItemClick(event.target.closest(".food_item"));
        }
    })
}

function handleOnItemClick(target) {
    if (isNoTextSelected()) {
        const itemID = parseInt(target.dataset.id, 10);
        fetchItemDialogData(itemID);
    }
}

function isNoTextSelected() {
    const noTextSelected = !window.getSelection().toString();
    return noTextSelected;
}

function fetchItemDialogData(itemID) {
    const url = apiUrl + "?" + new URLSearchParams({
        type: "data",
        id: itemID
    }).toString();
    fetch(url)
        .then(FetchHelper.onFulfilled)
        .then(({ details }) => {
            const item = details[0];
            setSelectedItem(item);
            showItemDialog(item);
        })
        .catch(FetchHelper.onRejected);
}

function showItemDialog({ label, id, nama, gambar, harga, maklumat }) {
    itemDialog.label = label + id + " : " + nama;
    itemDialog.querySelector(".dialog_image").src = gambar;
    itemDialog.querySelector(".dialog_price").innerHTML = "Harga : RM " + harga;
    itemDialog.querySelector(".dialog_description").innerHTML = maklumat;
    itemDialog.show();
}

function setSelectedItem(item) {
    selectedItem = item;
    selectedItem.kuantiti = 1;
}

function addSelectedItemToCart() {
    eventBus.emit("addItemToCart", {
        item: selectedItem
    });
}

function updateItemQuantity(value) {
    selectedItem.kuantiti = value;
}

function resetItemDialog() {
    selectedItem = {};
    dialogAmount.value = 1;
}

