globalThis.cart = [];

const addItemButton = document.querySelector(".add_item_button");
const cartButton = document.querySelector(".cart_button");
const cartDiv = document.querySelector(".cart");

cartButton.addEventListener("click", () => {
    const cartDialog = cartDiv.querySelector(".cart_dialog");
    cartDialog.show();
});

addItemButton.addEventListener("click", async () => {
    console.log(cart)
    console.log(addItemButton.value.kuantiti)
    addItemToCart(addItemButton.value);
    const itemDialog = document.querySelector(".item_dialog");
    itemDialog.hide();
});

function addItemToCart(item) {
    console.log(cart)
    for (let i = 0; i < cart.length; i++) {
        if (cart[i].id == item.id) {
            console.log(item.kuantiti)
            cart[i].kuantiti += item.kuantiti;
            console.log(cart[i])
            updateItemQuantityInCartDialog(i, cart[i].kuantiti);
            return;
        }
    }
    cart.push(item);
    addItemToCartDialog(item);
}

function updateItemQuantityInCartDialog(itemIndex, quantity) {
    const itemList = cartDiv.querySelector(".item_list");
    const itemElement = itemList.children[itemIndex];
    itemElement.itemValue.kuantiti = quantity;
    const item = itemElement.itemValue;
    itemElement.innerHTML = `${item.label + item.nombor} ${item.nama} : ${item.kuantiti}`;
}

function addItemToCartDialog(item) {
    const itemList = cartDiv.querySelector(".item_list");
    let itemElement = document.createElement("li");
    itemElement.itemValue = item;
    itemElement.innerHTML = `${item.label + item.nombor} ${item.nama} : ${item.kuantiti}`;
    itemList.appendChild(itemElement);
}
