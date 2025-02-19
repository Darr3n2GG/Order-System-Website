const data_path = "/Order-System-Website/src/backend/fetchItem.php"

document.addEventListener("DOMContentLoaded", () => {
    fetch(data_path)
        .then(onFulfilled, onRejected)
        .then(data => setupMenu(data))
        .catch(error => console.error("Error loading categories:", error));
});

function setupMenu(data) {
    setupKategori(data.kategori);
    setupMakanan(data.makanan);
}

function setupKategori(kategori_list) {
    displayDropdown(kategori_list);
    displayKategori(kategori_list);
}

function displayDropdown(kategori_list) {
    const dropdownMenu = document.querySelector(".category_menu");
    kategori_list.forEach(kategori => {
        const menuItemElement = document.createElement("sl-menu-item");
        menuItemElement.value = kategori.label;
        menuItemElement.innerHTML = kategori.nama;
        dropdownMenu.appendChild(menuItemElement);
    });
}

function displayKategori(kategori_list) {
    const menu = document.querySelector(".menu");
    kategori_list.forEach(kategori => {
        const kategoriElement = document.createElement("div");
        kategoriElement.classList.add("kategori");
        kategoriElement.id = kategori.label;
        kategoriElement.innerHTML = `<h1>${kategori.nama}</h1>`;
        menu.appendChild(kategoriElement);
    });
}

function setupMakanan(makanan_list) {
    displayMakanan(makanan_list);
    addClickEvent(makanan_list);
}

function displayMakanan(makanan_list) {
    makanan_list.forEach(item => {
        const kategori = document.getElementById(item.label);
        const foodItem = document.createElement("div");
        foodItem.classList.add("food_item")
        foodItem.innerHTML = `
            <img src="${item.gambar}" alt="${item.nama}">
            <div class="food_info">
                <div class="food_row">
                    <h2>${item.nama}</h2>
                    <sl-tag size="small" pill>${item.label}</sl-tag>
                </div>
                <div class="food_row">
                    <p><strong>Harga : RM${item.harga}</strong></p>
                    <sl-icon-button
                        class="dialog_button" name="plus-square">
                    </sl-icon-button>
                </div>
            </div>
        `;
        kategori.appendChild(foodItem);
    });
}

function addClickEvent(makanan_list) {
    const dialogButton = document.getElementsByClassName("dialog_button");
    for (let i = 0; i < dialogButton.length; i++) {
        dialogButton[i].addEventListener("click", () => {
            showDialog(makanan_list[i]);
        });
    }
}

function showDialog(makanan) {
    const dialog = document.querySelector(".item_dialog");
    dialog.label = makanan.label + " : " + makanan.nama;
    dialog.querySelector(".dialog_image").src = makanan.gambar;
    dialog.querySelector(".dialog_price").innerHTML = "Harga : RM" + makanan.harga;

    dialog.show();
}

// const finalMakanan = addQuantityToMakanan(makanan);
// const addItemButton = dialog.querySelector(".add_item_button");
// addItemButton.value = finalMakanan;

// Quantity of the exact same food items
function addQuantityToMakanan(makanan) {
    let finalMakanan = makanan
    finalMakanan.quantiti = 1
    return finalMakanan
}

const onFulfilled = (response) => {
    if (response.status !== 200 && !response.ok) {
        throw new Error(`[${response.status}] Unable to fetch resource`);
    }
    return response.json();
}
  
const onRejected = (err) => {
    console.error(err);
}