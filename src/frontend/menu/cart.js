import { eventBus } from "../../scripts/EventBus.js";
import { Cart } from "../../scripts/Cart.js";

const cart = new Cart;
// cart logger in console, type "logCart()"
globalThis.logCart = () => cart.getCart();

const cartDialog = document.querySelector(".cart_dialog");
const cartDialogItemList = cartDialog.querySelector(".cart_dialog_items");

const cartBox = document.querySelector(".cart_container");
const cartItemList = cartBox.querySelector(".cart_items");

eventBus.addEventListener("AddCartItem", ({ detail }) => {
    cartDialog.querySelector(".cart_dialog_empty").classList.add("hide");
    cartBox.querySelector(".cart_empty").classList.add("hide");
    addCartDialogItemUI(detail.newItem);
    addCartItemUI(detail.newItem)
    updateTotalPrice();
})

eventBus.addEventListener("ItemQuantityUpdated", ({ detail }) => {
    const dialogItemElement = findCartDialogItemElement(detail.item.id);
    const itemElement = findCartItemElement(detail.item.id);
    if (!itemElement || !dialogItemElement) {
        console.error("One or more item elements do not exist.")
        return;
    }
    const item = detail.item
    updateDialogItemTotalPrice(item.harga, item.kuantiti, dialogItemElement);
    updateDialogItemInput(item.kuantiti, dialogItemElement);
    updateItemTotalPrice(item.harga, item.kuantiti, itemElement);
    updateItemInput(item.kuantiti, itemElement)
    updateTotalPrice();
})

document.querySelector(".cart_button").addEventListener("click", () => {
    cartDialog.show();
});

cartDialog.querySelector(".dialog_checkout_button").addEventListener("click", () => {
    checkout();
})

cartBox.querySelector(".checkout_button").addEventListener("click", () => {
    checkout();
})

function checkout() {
    if (cart.getCart().length !== 0) {
        eventBus.emit("checkout", cart.getCart());
    } else {
        alert("No items in cart! Can't checkout.")
    }
}

cartDialogItemList.addEventListener("sl-change", ({ target }) => {
    if (target.classList.contains("cart_dialog_item_input")) {
        const id = parseInt(target.closest(".cart_dialog_item").dataset.id, 10);
        const value = parseInt(target.value, 10)
        cart.updateItemKuantitiWithId(id, value);
        updateCartUI(id);
    }
})

cartItemList.addEventListener("sl-change", ({ target }) => {
    if (target.classList.contains("cart_spinbox_input")) {
        const id = parseInt(target.closest(".cart_item").dataset.id, 10);
        const value = parseInt(target.value, 10)
        cart.updateItemKuantitiWithId(id, value);
        updateCartUI(id);
    }
})

function updateCartUI(id) {
    const dialogItemElement = findCartDialogItemElement(id);
    const itemElement = findCartItemElement(id);
    if (!itemElement || !dialogItemElement) {
        console.error("One or more item elements do not exist.")
        return;
    }
    const cartItem = cart.findCartItem(id);
    updateDialogItemTotalPrice(cartItem.harga, cartItem.kuantiti, dialogItemElement);
    updateItemTotalPrice(cartItem.harga, cartItem.kuantiti, itemElement)
    updateTotalPrice();
}

cartDialogItemList.addEventListener("click", ({ target }) => {
    if (target.classList.contains("cart_dialog_delete_item")) {
        deleteCartDialogItemUI(target.closest(".cart_dialog_item"));
        updateTotalPrice();
    }
})

cartItemList.addEventListener("click", ({ target }) => {
    if (target.classList.contains("cart_delete_item")) {
        deleteCartItemUI(target.closest(".cart_item"));
        updateTotalPrice();
    }
})


function addCartDialogItemUI({ id, label, nama, kuantiti, harga }) {
    cartDialogItemList.insertAdjacentHTML("beforeend",
        `<div data-id="${id}" class="cart_dialog_item">
            <div class="cart_dialog_item_info">
                <span>${label + id} ${nama}</span>
                <span class="cart_dialog_item_price">RM ${harga * kuantiti}</span>
            </div>
            <div class="cart_dialog_item_options">
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
            </div>
        </div>`
    );
}

function addCartItemUI({ id, label, nama, kuantiti, harga, gambar }) {
    cartItemList.insertAdjacentHTML("beforeend",
        `<div data-id="${id}" class="cart_item">
            <img src="${gambar}" alt="${nama}">
            <div class="cart_item_data">
                <div class="cart_item_label">
                    <span class="cart_item_name">${label + id} ${nama}</span>
                    <span class="cart_item_price">RM ${harga * kuantiti}</span>
                </div>
                <div class="cart_item_options">
                    <sl-button-group class="spinbox">
                        <sl-button class="spinbox_decrement" variant="default" size="small" pill>
                            <sl-icon name="dash-lg"></sl-icon>
                        </sl-button>
                        <sl-input class="spinbox_input cart_spinbox_input" type="number" value="${kuantiti}" size="small" no-spin-buttons></sl-input>
                        <sl-button class="spinbox_increment" variant="default" size="small" pill>
                            <sl-icon name="plus-lg"></sl-icon>
                        </sl-button>
                    </sl-button-group>
                    <sl-icon-button class="cart_delete_item" name="trash"></sl-icon-button>
                </div>
            </div>
        </div>`
    );
}

function updateDialogItemTotalPrice(harga, kuantiti, itemElement) {
    const dialogItemTotalPrice = itemElement.querySelector(".cart_dialog_item_price");
    if (!dialogItemTotalPrice) {
        console.error("Item total price element doesn't exist.")
    }
    dialogItemTotalPrice.innerHTML = "RM " + (harga * kuantiti)
}

function updateItemTotalPrice(harga, kuantiti, itemElement) {
    const itemTotalPrice = itemElement.querySelector(".cart_item_price");
    if (!itemTotalPrice) {
        console.error("Item total price element doesn't exist.")
    }
    itemTotalPrice.innerHTML = "RM " + (harga * kuantiti)
}

function updateDialogItemInput(kuantiti, itemElement) {
    const itemInput = itemElement.querySelector(".cart_dialog_item_input");
    if (!itemInput) {
        console.error("Dialog item input doesn't exist.")
    }
    itemInput.value = kuantiti;
}

function updateItemInput(kuantiti, itemElement) {
    const itemInput = itemElement.querySelector(".cart_spinbox_input");
    if (!itemInput) {
        console.error("Item input doesn't exist.")
    }
    itemInput.value = kuantiti;
}

function updateTotalPrice() {
    const dialogTotalPrice = cartDialog.querySelector(".cart_dialog_total_price");
    const totalPrice = cartBox.querySelector(".cart_total_price");
    if (!totalPrice || !dialogTotalPrice) {
        console.error("One or more total price elements do not exist.");
        return;
    }
    const html = "Jumlah Harga : RM " + cart.calculateTotalPrice();
    dialogTotalPrice.innerHTML = html;
    totalPrice.innerHTML = html;
}

function deleteCartDialogItemUI(element) {
    const id = parseInt(element.dataset.id, 10);
    cart.deleteCartItem(id);
    element.remove();
    if (cart.getCart().length === 0) {
        cartDialog.querySelector(".cart_dialog_empty").classList.remove("hide");
    }
}

function deleteCartItemUI(element) {
    const id = parseInt(element.dataset.id, 10);
    cart.deleteCartItem(id);
    element.remove();
    if (cart.getCart().length === 0) {
        cartBox.querySelector(".cart_empty").classList.remove("hide");
    }
}

function findCartDialogItemElement(id) {
    const items = Array.from(cartDialogItemList.children);
    const itemResult = items.find(child => parseInt(child.dataset.id, 10) === id);
    if (itemResult !== undefined) {
        return itemResult;
    }
    return null;
}

function findCartItemElement(id) {
    const items = Array.from(cartItemList.children);
    const itemResult = items.find(child => parseInt(child.dataset.id, 10) === id);
    if (itemResult !== undefined) {
        return itemResult;
    }
    return null;
}