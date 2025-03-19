import { eventBus } from "../../scripts/EventBus.js";
import { Cart } from "../../scripts/Cart.js";

const cart = new Cart;
// cart logger in console, type "logCart()"
globalThis.logCart = () => cart.getCart();

const cartDialog = document.querySelector(".cart_dialog");
const itemList = cartDialog.querySelector(".cart_item_list");

eventBus.addEventListener("AddCartItem", ({ detail }) => {
    cartDialog.querySelector(".cart_empty").classList.add("hide");
    addCartItemUI(detail.newItem);
    updateTotalPrice();
})

eventBus.addEventListener("ItemQuantityUpdated", ({ detail }) => {
    const itemElement = findCartItemElement(id);
    if (!itemElement) {
        console.error("Item element doesn't exist.")
    }
    const item = detail.item
    updateItemTotalPrice(item.harga, item.kuantiti, itemElement)
    updateItemInput(item.kuantiti, itemElement)
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
        const value = parseInt(target.value, 10)
        cart.updateCartItem(id, value);
        updateCartUI(id)
    }

    function updateCartUI(id) {
        const itemElement = findCartItemElement(id);
        if (!itemElement) {
            console.error("Item element doesn't exist.")
        }
        const cartItem = cart.findCartItem(id);
        updateItemTotalPrice(cartItem.harga, cartItem.kuantiti, itemElement)
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

function updateItemTotalPrice(harga, kuantiti, itemElement) {
    const itemTotalPrice = itemElement.querySelector(".item_total_price");
    if (!itemTotalPrice) {
        console.error("Item total price element doesn't exist.")
    }
    itemTotalPrice.innerHTML = "RM " + (harga * kuantiti)
}

function updateItemInput(kuantiti, itemElement) {
    const itemInput = itemElement.querySelector(".cart_item_input");
    if (!itemInput) {
        console.error("Item input doesn't exist.")
    }
    itemInput.value = kuantiti;
}

function updateTotalPrice() {
    const totalPrice = cartDialog.querySelector(".total_price");
    if (!totalPrice) {
        console.error("Total price element doesn't exist.")
    }
    totalPrice.innerHTML = "Jumlah Harga : RM " + cart.calculateTotalPrice();
}

function deleteCartItemUI(element) {
    const id = parseInt(element.dataset.id, 10);
    cart.deleteCartItem(id);
    element.remove();
    if (cart.getCart.length === 0) {
        cartDialog.querySelector(".cart_empty").classList.remove("hide");
    }
}

function findCartItemElement(id) {
    const items = Array.from(itemList.children);
    const itemResult = items.find(child => parseInt(child.dataset.id, 10) === id);
    if (itemResult !== undefined) {
        return itemResult;
    }
    return null;
}