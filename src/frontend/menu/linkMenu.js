const categoryDropdown = document.getElementById("category-dropdown");

categoryDropdown.addEventListener('sl-select', event => {
    const selectedItem = event.detail.item;
    document.getElementById(selectedItem.value).scrollIntoView();
});