const cart = [];

const addItemButton = document.querySelector(".add_item_button");
const cartButton = document.querySelector(".cart_button");
const cartDiv = document.querySelector(".cart");

addItemButton.addEventListener("click", () => {
    addItemToCart(addItemButton.value);
    const itemDialog = document.querySelector(".item_dialog");
    itemDialog.hide();
});

cartButton.addEventListener("click", () => {
    const cartDialog = cartDiv.querySelector(".cart_dialog");
    cartDialog.show();
})

function addItemToCart(item) {
    cart.forEach(cartItem => {
        if (cartItem.id == item.id) {
            cartItem.quantiti += item.quantiti;
            addItemToCartDialog(item);
            return;
        }
    });
    cart.push(item);
    addItemToCartDialog(item);
}

function addItemToCartDialog(item) {
    const itemList = cartDiv.querySelector(".item_list");
    const itemElement = document.createElement("li");
    itemElement.innerHTML = `${item.label + item.nombor} ${item.nama} : ${item.quantiti}`;
    itemList.appendChild(itemElement);
}

function checkIfItemExistsInCart(item) {
    cart.forEach(cartItem => {
        if (cartItem.id == item.id) {
            return true;
        }
    });
    return false;
}
