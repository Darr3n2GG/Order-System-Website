import fetchHelper from "../../scripts/fetchHelper.js";
import { eventBus } from "../../scripts/eventBus.js";

// Setup Menu

document.addEventListener("DOMContentLoaded", () => {
    const url = "/Order-System-Website/src/backend/fetchMenuData.php"
    fetch(url)
        .then(fetchHelper.onFulfilled,fetchHelper.onRejected)
        .then(data => setupMenu(data));
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
    eventBus.emit("setupItemDialog");
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

// Menu Linking

const categoryDropdown = document.querySelector(".category_dropdown");

categoryDropdown.addEventListener('sl-select', event => {
    const selectedKategori = event.detail.item;
    document.getElementById(selectedKategori.value).scrollIntoView();
});