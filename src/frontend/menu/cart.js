const cart = [];

const addItemButton = document.querySelector(".add_item_button");
const cartButton = document.querySelector(".cart_button");
const cartDiv = document.querySelector(".cart");

cartButton.addEventListener("click", () => {
    const cartDialog = cartDiv.querySelector(".cart_dialog");
    cartDialog.show();
});

addItemButton.addEventListener("click", () => {
    addItemToCart(addItemButton.value);
    const itemDialog = document.querySelector(".item_dialog");
    itemDialog.hide();
});

function addItemToCart(item) {
    for (let i = 0; i < cart.length; i++) {
        if (cart[i].id == item.id) {
            cart[i].quantiti += item.quantiti;
            updateItemQuantityInCartDialog(i, item.quantiti);
            return;
        }
    }
    cart.push(item);
    addItemToCartDialog(item);
}

function updateItemQuantityInCartDialog(itemIndex, quantity) {
    const itemList = cartDiv.querySelector(".item_list");
    const itemElement = itemList.children[itemIndex];
    itemElement.value.quantiti += quantity;
    item = itemElement.value;
    itemElement.innerHTML = `${item.label + item.nombor} ${item.nama} : ${item.quantiti}`;
}

function addItemToCartDialog(item) {
    const itemList = cartDiv.querySelector(".item_list");
    let itemElement = document.createElement("li");
    itemElement.value = item;
    itemElement = updateItemHTML(itemElement);
    itemList.appendChild(itemElement);
}

function updateItemHTML(itemElement) {
    const item = itemElement.value;
    itemElement.innerHTML = `${item.label + item.nombor} ${item.nama} : ${item.quantiti}`;
    return itemElement;
}

function checkIfItemExistsInCart(item) {
    cart.forEach(cartItem => {
        if (cartItem.id == item.id) {
            return true;
        }
    });
    return false;
}
