import { eventBus } from "../../scripts/EventBus.js";
import { Cart } from "../../scripts/CartAPI.js";

const cart = new Cart;
// cart logger in console, type "logCart()"
globalThis.logCart = () => cart.getCart();

const cartDialog = document.querySelector(".cart_dialog");
const itemList = cartDialog.querySelector(".cart_item_list");

const cartButton = document.querySelector(".cart_button");
cartButton.addEventListener("click", () => {
    cartDialog.show();
});

const checkoutButton = cartDialog.querySelector(".checkout_button");
checkoutButton.addEventListener("click", () => {
    if (cart.length !== 0) {
        eventBus.emit("checkout", cart.getCart());
    }
})

eventBus.addEventListener("addItemToCartDialog", ({ detail }) => {
    addItemToCartDialog(detail.newItem);
    if (cart.getCart().length !== 0) {
        cartDialog.querySelector(".cart_empty").classList.add("cart_has_item");
    }
})

eventBus.addEventListener("updateItemQuantityInCartDialog", ({ detail }) => {
    updateItemQuantityInCartDialog(detail.item);
})

itemList.addEventListener("sl-change", ({ target }) => {
    if (target.classList.contains("cart_item_input")) {
        const parent = target.parentNode;
        const itemID = parseInt(parent.dataset.id, 10);
        const newValue = parseInt(target.value ,10)
        cart.updateCartItemQuantity(itemID, newValue);
    }
})

itemList.addEventListener("click", ({ target }) => {
    if (target.classList.contains("cart_delete_item")) {
        const parent = target.parentNode;
        const itemID = parseInt(parent.dataset.id, 10);
        cart.deleteItem(itemID);
        parent.remove();
        if (cart.getCart.length === 0) {
            cartDialog.querySelector(".cart_empty").classList.remove("cart_has_item");
        }
    }
})


function addItemToCartDialog({ id, label, nama, kuantiti }) {
    itemList.insertAdjacentHTML("beforeend", 
        `<li data-id="${id}" class="cart_item">
            <p>${label + id} ${nama}</p>
            <sl-input class="cart_item_input" type="number" value="${kuantiti}" size="small" required></sl-input>
            <sl-icon-button class="cart_delete_item" name="trash"></sl-icon-button>
        </li>`);
}

function updateItemQuantityInCartDialog(item) {
    const itemElement = findCartItemElementByID(item.id);
    if (itemElement !== null) {
        const itemInput = itemElement.querySelector(".cart_item_input");
        itemInput.value = item.kuantiti;
    }
}

function findCartItemElementByID(id) {
    const items = Array.from(itemList.children);
    const itemResult = items.find(child => parseInt(child.dataset.id, 10) === id);
    if (itemResult !== undefined) {
        return itemResult;
    }
    return null;
}