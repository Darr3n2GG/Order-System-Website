const categoryDropdown = document.querySelector(".category_dropdown");

categoryDropdown.addEventListener('sl-select', event => {
    const selectedItem = event.detail.item;
    document.getElementById(selectedItem.value).scrollIntoView();
});

const dialogAmount = document.querySelector(".dialog_amount");

dialogAmount.addEventListener("sl-change", () => {
    updateItemQuantity(dialogAmount.value);
});

function updateItemQuantity(value) {
    const addItemButton = document.querySelector(".add_item_button");
    addItemButton.value.quantiti = parseInt(value);
}