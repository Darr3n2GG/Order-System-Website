import { eventBus } from "../../scripts/eventBus.js";

const cart = [];
// cart logger in console, type "logCart()"
globalThis.logCart = (function(){
    return cart;
});

const cartButton = document.querySelector(".cart_button");
const cartDialog = document.querySelector(".cart_dialog");
const itemList = cartDialog.querySelector(".cart_item_list");
const checkoutButton = cartDialog.querySelector(".checkout_button");

eventBus.addEventListener("addItemToCart", event => {
    addItemToCart(event.detail.item);
});

cartButton.addEventListener("click", () => {
    cartDialog.show();
});

checkoutButton.addEventListener("click", () => {
    if (cart.length != 0) {
        eventBus.emit("checkout", cart);
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

function updateCartItemQuantity(id, value) {
    const cartItem = findCartItemByID(id);
    cartItem.kuantiti = value;
}

function addItemToCart(item) {
    const cartItem = findCartItemByID(item.id);
    if (cartItem != false) {
        cartItem.kuantiti += item.kuantiti;
        updateItemQuantityInCartDialog(cartItem);
    } else {
        const cartItem = {
            id : item.id,
            kuantiti : item.kuantiti,
        }
        cart.push(cartItem);
        addItemToCartDialog(item);
    }
}

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

function findCartItemByID(id) {
    for (let i = 0; i < cart.length; i++) {
        const item = cart[i];
        if (item.id === id) {
            return item;
        }
    }
    return false;
}

function findCartItemElementByID(id) {
    for (let i = 0; i < cart.length; i++) {
        const child = itemList.children[i];
        const childID = parseInt(child.dataset.id);
        if (childID === id) {
            return child;
        }
    }
    return false;
}
