import { eventBus } from "../../scripts/eventBus.js";

const cart = [];
// cart logger in console, type "logCart()"
globalThis.logCart = (function(){
    return cart;
});

const cartButton = document.querySelector(".cart_button");
const cartDialog = document.querySelector(".cart_dialog");
const itemList = cartDialog.querySelector(".item_list");
const checkoutButton = cartDialog.querySelector(".checkout_button");

eventBus.addEventListener("addItemToCart", data => {
    addItemToCart(data.detail.item);
});

cartButton.addEventListener("click", () => {
    cartDialog.show();
});

checkoutButton.addEventListener("click", () => {
    eventBus.emit("checkout", cart);
})

function addItemToCart(item) {
    for (let i = 0; i < cart.length; i++) {
        if (cart[i].id === item.id) {
            cart[i].kuantiti += item.kuantiti;
            updateItemQuantityInCartDialog(i, cart[i].kuantiti);
            return;
        }
    }
    cart.push(item);
    addItemToCartDialog(item);
}

function addItemToCartDialog(item) {
    let itemElement = document.createElement("li");
    itemElement.innerHTML = `${item.label + item.id} ${item.nama} : ${item.kuantiti}`;
    itemList.appendChild(itemElement);
}

function updateItemQuantityInCartDialog(itemIndex, quantity) {
    const itemElement = itemList.children[itemIndex];
    const item = cart[itemIndex];
    itemElement.innerHTML = `${item.label + item.id} ${item.nama} : ${quantity}`;
}
