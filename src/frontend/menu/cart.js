const cart = [];

const add_item_button = document.querySelector(".add_item_button");

add_item_button.addEventListener("click", () => {
    addItemToCart(add_item_button.value);
});

function addItemToCart(item) {
    cart.push(item);
}