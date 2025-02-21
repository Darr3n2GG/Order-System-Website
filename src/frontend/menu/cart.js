import { eventBus } from "../../scripts/eventBus.js";

const cart = [];
// cart Getter in console, type "logCart()"
globalThis.cart = (function(){
    return cart;
});

const cartButton = document.querySelector(".cart_button");
const cartDiv = document.querySelector(".cart");

eventBus.addEventListener("addItemToCart", data => {
    addItemToCart(data.detail.item);
});

cartButton.addEventListener("click", () => {
    const cartDialog = cartDiv.querySelector(".cart_dialog");
    cartDialog.show();
});

function addItemToCart(item) {
    for (let i = 0; i < cart.length; i++) {
        if (cart[i].id == item.id) {
            cart[i].kuantiti += item.kuantiti;
            updateItemQuantityInCartDialog(i, cart[i].kuantiti);
            return;
        }
    }
    cart.push(item);
    addItemToCartDialog(item);
}

function updateItemQuantityInCartDialog(itemIndex, quantity) {
    const itemList = cartDiv.querySelector(".item_list");
    const itemElement = itemList.children[itemIndex];
    itemElement.itemValue.kuantiti = quantity;
    const item = itemElement.itemValue;
    itemElement.innerHTML = `${item.label + item.id} ${item.nama} : ${item.kuantiti}`;
}

function addItemToCartDialog(item) {
    const itemList = cartDiv.querySelector(".item_list");
    let itemElement = document.createElement("li");
    itemElement.itemValue = item;
    itemElement.innerHTML = `${item.label + item.id} ${item.nama} : ${item.kuantiti}`;
    itemList.appendChild(itemElement);
}
