const categoryDropdown = document.querySelector(".category_dropdown");

categoryDropdown.addEventListener('sl-select', event => {
    const selectedKategori = event.detail.item;
    document.getElementById(selectedKategori.value).scrollIntoView();
});