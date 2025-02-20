const categoryDropdown = document.querySelector(".category_dropdown");

categoryDropdown.addEventListener('sl-select', event => {
    const selectedItem = event.detail.item;
    document.getElementById(selectedItem.value).scrollIntoView();
});

const itemDialog = document.querySelector(".item_dialog");
const dialogAmount = itemDialog.querySelector(".dialog_input");
const addItemButton = document.querySelector(".add_item_button");

itemDialog.addEventListener("sl-after-hide", () => {
    resetItemDialog();
});

function resetItemDialog() {
    dialogAmount.value = 1;
}

dialogAmount.addEventListener("sl-change", () => {
    updateItemQuantity(parseInt(dialogAmount.value));
});

function updateItemQuantity(value) {
    addItemButton.value.kuantiti = value;
}