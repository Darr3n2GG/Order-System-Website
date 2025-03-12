const apiUrl = "/Order-System-Website/src/backend/MakananAPI.php";

const categoryDropdown = document.querySelector(".category_dropdown");

categoryDropdown.addEventListener('sl-select', ({ detail }) => {
    const selectedKategori = detail.item;
    document.getElementById(selectedKategori.value).scrollIntoView();
});

document.addEventListener("DOMContentLoaded", () => {
    const url = apiUrl + "?" + new URLSearchParams({
        id : item_id
    }).toString();
    fetch()
})