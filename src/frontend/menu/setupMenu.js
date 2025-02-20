import fetchHelper from "../../scripts/fetchHelper.js";

document.addEventListener("DOMContentLoaded", () => {
    const url = "/Order-System-Website/src/backend/fetchMenuData.php"
    fetch(url)
        .then(fetchHelper.onFulfilled,fetchHelper.onRejected)
        .then(data => setupMenu(data))
        .catch(error => console.error("Error loading data:", error));
});

function setupMenu(data) {
    setupKategori(data.kategori);
    setupMakanan(data.makanan);
}

function setupKategori(list_kategori) {
    displayDropdown(list_kategori);
    displayKategori(list_kategori);
}

function displayDropdown(list_kategori) {
    const dropdownMenu = document.querySelector(".category_menu");
    list_kategori.forEach(kategori => {
        const menuItemElement = document.createElement("sl-menu-item");
        menuItemElement.value = kategori.label;
        menuItemElement.innerHTML = kategori.nama;
        dropdownMenu.appendChild(menuItemElement);
    });
}

function displayKategori(list_kategori) {
    const menu = document.querySelector(".menu");
    list_kategori.forEach(kategori => {
        const kategoriElement = document.createElement("div");
        kategoriElement.classList.add("kategori");
        kategoriElement.id = kategori.label;
        kategoriElement.innerHTML = `<h1>${kategori.nama}</h1>`;
        menu.appendChild(kategoriElement);
    });
}

function setupMakanan(list_makanan) {
    displayMakanan(list_makanan);
    setupItemDialog();
}

function displayMakanan(list_makanan) {
    list_makanan.forEach(item => {
        const kategori = document.getElementById(item.label);
        const foodItem = document.createElement("div");
        foodItem.classList.add("food_item")
        foodItem.innerHTML = `
            <img src="${item.gambar}" alt="${item.nama}">
            <div class="food_info">
                <div class="food_row">
                    <h2>${item.nama}</h2>
                    <sl-tag size="small" pill>${item.label + item.id}</sl-tag>
                </div>
                <div class="food_row">
                    <p><strong>Harga : RM${item.harga}</strong></p>
                    <sl-icon-button
                        class="dialog_button" name="plus-square" id="${item.id}">
                    </sl-icon-button>
                </div>
            </div>
        `;
        kategori.appendChild(foodItem);
    });
}

// Item Dialog
function setupItemDialog() {
    const dialogButton = document.getElementsByClassName("dialog_button");
    for (let i = 0; i < dialogButton.length; i++) {
        dialogButton[i].addEventListener("click", () => {
            const itemID = dialogButton[i].id;
            fetchAndShowItemDialog(itemID);
        });
    }
}

function fetchAndShowItemDialog(item_id) {
    const url = "/Order-System-Website/src/backend/fetchMakanan.php?" + new URLSearchParams({
        id : item_id
    }).toString();
    fetch(url)
        .then(fetchHelper.onFulfilled, fetchHelper.onRejected)
        .then(item => showItemDialog(item))
        .catch(error => console.error("Error loading item:", error));
}

function showItemDialog(item) {
    const dialog = document.querySelector(".item_dialog");
    dialog.label = item.label + item.id +  " : " + item.nama;
    dialog.querySelector(".dialog_image").src = item.gambar;
    dialog.querySelector(".dialog_price").innerHTML = "Harga : RM" + item.harga;
    dialog.show();
}