const cart = [];

const addItemButton = document.querySelector(".add_item_button");
const cartButton = document.querySelector(".cart_button");
const cartDiv = document.querySelector(".cart");

addItemButton.addEventListener("click", () => {
    addItemToCart(addItemButton.value);
});

cartButton.addEventListener("click", () => {
    const cartDialog = cartDiv.querySelector(".cart_dialog");
    cartDialog.show();
})

function addItemToCart(item) {
    cart.push(item);
    const itemList = cartDiv.querySelector(".item_list");
    const itemElement = document.createElement("li");
    itemElement.innerHTML = `${cart.label + cart.nombor} ${cart.nama} : ${cart.quantiti}`;
    itemList.appendChild(itemElement);
}

