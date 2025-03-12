import { eventBus } from "../../scripts/EventBus.js";
import { Cart } from "../../scripts/CartAPI.js";

const cart = new Cart;
// cart logger in console, type "logCart()"
globalThis.logCart = () => cart.getCart();

const cartDialog = document.querySelector(".cart_dialog");
const itemList = cartDialog.querySelector(".cart_item_list");

eventBus.addEventListener("AddCartItem", ({ detail }) => {
    addCartItemUI(detail.newItem);
    if (cart.getCart().length !== 0) {
        cartDialog.querySelector(".cart_empty").classList.add("cart_has_item");
    }
})

eventBus.addEventListener("ItemQuantityUpdated", ({ detail }) => {
    updateCartItemUI(detail.item);
})

document.querySelector(".cart_button").addEventListener("click", () => {
    cartDialog.show();
});

cartDialog.querySelector(".checkout_button").addEventListener("click", () => {
    if (cart.getCart().length !== 0) {
        eventBus.emit("checkout", cart.getCart());
    } else {
        alert("No items in cart! Can't checkout.")
    }
})

itemList.addEventListener("sl-change", ({ target }) => {
    if (target.classList.contains("cart_item_input")) {
        const id = parseInt(target.parentNode.dataset.id, 10);
        const value = parseInt(target.value ,10)
        cart.updateCartItem(id, value)
    }
})

itemList.addEventListener("click", ({ target }) => {
    if (target.classList.contains("cart_delete_item")) {
        const id = parseInt(target.parentNode.dataset.id, 10);
        cart.deleteCartItem(id);
        deleteCartItemUI(target.parentNode)
        if (cart.getCart.length === 0) {
            cartDialog.querySelector(".cart_empty").classList.remove("cart_has_item");
        }
    }
})


function addCartItemUI({ id, label, nama, kuantiti }) {
    itemList.insertAdjacentHTML("beforeend", 
        `<li data-id="${id}" class="cart_item">
            <p>${label + id} ${nama}</p>
            <sl-input class="cart_item_input" type="number" value="${kuantiti}" size="small" required></sl-input>
            <sl-icon-button class="cart_delete_item" name="trash"></sl-icon-button>
        </li>`
    );
}

function updateCartItemUI(item) {
    const itemElement = findCartItemElement(item.id);
    if (itemElement !== null) {
        const itemInput = itemElement.querySelector(".cart_item_input");
        itemInput.value = item.kuantiti;
    } else {
        console.error("Item element doesn't exist.")
    }
}

function deleteCartItemUI(element) {
    element.remove();
}

function findCartItemElement(id) {
    const items = Array.from(itemList.children);
    const itemResult = items.find(child => parseInt(child.dataset.id, 10) === id);
    if (itemResult !== undefined) {
        return itemResult;
    }
    return null;
}