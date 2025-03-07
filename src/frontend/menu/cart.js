import { eventBus } from "../../scripts/eventBus.js";
import { Cart } from "../../scripts/CartAPI.js";

const cart = new Cart;
// cart logger in console, type "logCart()"
globalThis.logCart = (function(){
    return cart.getCart();
});

const cartButton = document.querySelector(".cart_button");
const cartDialog = document.querySelector(".cart_dialog");
const itemList = cartDialog.querySelector(".cart_item_list");
const checkoutButton = cartDialog.querySelector(".checkout_button");

eventBus.addEventListener("addItemToCartDialog", event => {
    addItemToCartDialog(event.detail.newItem);
})

eventBus.addEventListener("updateItemQuantityInCartDialog", event => {
    updateItemQuantityInCartDialog(event.detail.item);
})

cartButton.addEventListener("click", () => {
    cartDialog.show();
});

checkoutButton.addEventListener("click", () => {
    if (cart.length != 0) {
        eventBus.emit("checkout", cart.getCart());
    }
})

itemList.addEventListener("sl-change", event => {
    if (event.target.classList.contains("cart_item_input")) {
        const parent = event.target.parentNode;
        const id = parseInt(parent.dataset.id);
        const value = parseInt(event.target.value);
        updateCartItemQuantity(id, value);
    }
})

function addItemToCartDialog(item) {
    let itemElement = document.createElement("li");
    itemElement.dataset.id = item.id;
    itemElement.classList.add("cart_item");
    itemElement.innerHTML = `<p>${item.label + item.id} ${item.nama}</p>
        <sl-input class="cart_item_input" type="number" value="${item.kuantiti}" size="small" required></sl-input>`;
    itemList.appendChild(itemElement);
}

function updateItemQuantityInCartDialog(item) {
    const itemElement = findCartItemElementByID(item.id);
    const itemInput = itemElement.querySelector(".cart_item_input");
    itemInput.value = item.kuantiti;
}

function findCartItemElementByID(id) {
    for (let i = 0; i < cart.getCartLength(); i++) {
        const child = itemList.children[i];
        const childID = parseInt(child.dataset.id);
        if (childID === id) {
            return child;
        }
    }
    return false;
}