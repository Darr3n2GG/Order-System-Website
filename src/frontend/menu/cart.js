import { eventBus } from "../../scripts/EventBus.js";
import { Cart } from "../../scripts/Cart.js";

const cart = new Cart;
// cart logger in console, type "logCart()"
globalThis.logCart = () => cart.getCart();

const cartDialog = document.querySelector(".cart_dialog");
const itemList = cartDialog.querySelector(".cart_dialog_items");

eventBus.addEventListener("AddCartItem", ({ detail }) => {
    cartDialog.querySelector(".cart_dialog_empty").classList.add("hide");
    addCartDialogItemUI(detail.newItem);
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
    if (target.classList.contains("cart_dialog_item_input")) {
        const id = parseInt(target.parentNode.parentNode.dataset.id, 10);
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
    if (target.classList.contains("cart_dialog_delete_item")) {
        deleteCartDialogItemUI(target.parentNode)
        updateTotalPrice();
    }
})


function addCartDialogItemUI({ id, label, nama, kuantiti, harga }) {
    itemList.insertAdjacentHTML("beforeend",
        `<div data-id="${id}" class="cart_dialog_item">
            <span>${label + id} ${nama}</span>
            <sl-button-group class="spinbox">
                <sl-button class="spinbox_decrement" variant="default" size="small" pill>
                    <sl-icon name="dash-lg"></sl-icon>
                </sl-button>
                <sl-input class="spinbox_input cart_dialog_item_input" type="number" value="${kuantiti}" size="small" no-spin-buttons required></sl-input>
                <sl-button class="spinbox_increment" variant="default" size="small" pill>
                    <sl-icon name="plus-lg"></sl-icon>
                </sl-button>
            </sl-button-group>
            <sl-icon-button class="cart_dialog_delete_item" name="trash"></sl-icon-button>
            <span class="item_total_price">RM ${harga}</span>
        </div>`
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
    const totalPrice = cartDialog.querySelector(".cart_dialog_total_price");
    if (!totalPrice) {
        console.error("Total price element doesn't exist.")
    }
    totalPrice.innerHTML = "Jumlah Harga : RM " + cart.calculateTotalPrice();
}

function deleteCartDialogItemUI(element) {
    const id = parseInt(element.dataset.id, 10);
    cart.deleteCartItem(id);
    element.remove();
    if (cart.getCart.length === 0) {
        cartDialog.querySelector(".cart_dialog_empty").classList.remove("hide");
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