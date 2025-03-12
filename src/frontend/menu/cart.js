import { eventBus } from "../../scripts/EventBus.js";
import { Cart } from "../../scripts/CartAPI.js";

const cart = new Cart;
// cart logger in console, type "logCart()"
globalThis.logCart = () => cart.getCart();

const cartDialog = document.querySelector(".cart_dialog");
const itemList = cartDialog.querySelector(".cart_item_list");

eventBus.addEventListener("AddCartItem", ({ detail }) => {
    cartDialog.querySelector(".cart_empty").classList.add("cart_has_item");
    addCartItemUI(detail.newItem);
    updateTotalPrice();
})

eventBus.addEventListener("ItemQuantityUpdated", ({ detail }) => {
    updateCartItemUI(detail.item);
    updateTotalPrice();
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
        cart.updateCartItem(id, value);
        const itemElement = findCartItemElement(id);
        if (itemElement !== null) {
            const cartItem = cart.findCartItem(id);
            const totalPrice = itemElement.querySelector(".item_total_price");
            totalPrice.innerHTML = "RM " + (cartItem.harga * cartItem.kuantiti)
        } else {
            console.error("Item element doesn't exist.")
        }
        updateTotalPrice();
    }
})

itemList.addEventListener("click", ({ target }) => {
    if (target.classList.contains("cart_delete_item")) {
        deleteCartItemUI(target.parentNode)
        updateTotalPrice();
    }
})


function addCartItemUI({ id, label, nama, kuantiti, harga }) {
    itemList.insertAdjacentHTML("beforeend", 
        `<li data-id="${id}" class="cart_item">
            <p>${label + id} ${nama}</p>
            <sl-input class="cart_item_input" type="number" value="${kuantiti}" size="small" required></sl-input>
            <sl-icon-button class="cart_delete_item" name="trash"></sl-icon-button>
            <p class="item_total_price">RM ${harga}</p>
        </li>`
    );
}

function updateCartItemUI({ id, kuantiti, harga }) {
    const itemElement = findCartItemElement(id);
    if (itemElement !== null) {
        const itemInput = itemElement.querySelector(".cart_item_input");
        const totalPrice = itemElement.querySelector(".item_total_price");
        itemInput.value = kuantiti;
        totalPrice.innerHTML = "RM " + (harga * kuantiti)
    } else {
        console.error("Item element doesn't exist.")
    }
}

function deleteCartItemUI(element) {
    const id = parseInt(element.dataset.id, 10);
    cart.deleteCartItem(id);
    element.remove();
    if (cart.getCart.length === 0) {
        cartDialog.querySelector(".cart_empty").classList.remove("cart_has_item");
    }
}

function updateTotalPrice() {
    const priceElement = cartDialog.querySelector(".total_price");
    priceElement.innerHTML = "Total Price : RM " + cart.calculateTotalPrice();
}

function findCartItemElement(id) {
    const items = Array.from(itemList.children);
    const itemResult = items.find(child => parseInt(child.dataset.id, 10) === id);
    if (itemResult !== undefined) {
        return itemResult;
    }
    return null;
}