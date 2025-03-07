const categoryDropdown = document.querySelector(".category_dropdown");

categoryDropdown.addEventListener('sl-select', ({ detail }) => {
    const selectedKategori = detail.item;
    document.getElementById(selectedKategori.value).scrollIntoView();
});