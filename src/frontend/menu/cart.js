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
    eventBus.emit("checkout", cart);
})

itemList.addEventListener("sl-change", event => {
    if (event.target.classList.contains("cart_item_input")) {
        $index = getIndexOfTargetCartItem(event.target);
        updateItemQuantityInCartDialog($index)
    }
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
    itemElement.classList.add("cart_item");
    itemElement.id = item.id
    itemElement.innerHTML = `${item.label + item.id} ${item.nama}
        <sl-input class="cart_item_input" type="number" value="${item.kuantiti}" size="small" required></sl-input>`;
    itemList.appendChild(itemElement);
}

function updateItemQuantityInCartDialog(itemIndex, quantity) {
    const itemElement = itemList.children[itemIndex];
    const item = cart[itemIndex];
    itemElement.innerHTML = `${item.label + item.id} ${item.nama} : ${quantity}`;
}

function getIndexOfTargetCartItem(target) {
    const itemListChildren = itemList.children;
    // TODO : add find target index based on given target element
    // OR
    // use getChildName in HTMLCollection class
}
