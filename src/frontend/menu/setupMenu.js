const kategori_path = "/Order-System-Website/src/backend/fetchKategori.php"
const makanan_path = "/Order-System-Website/src/backend/fetchMakanan.php"

document.addEventListener("DOMContentLoaded", async () => {
    await fetch(kategori_path)
        .then(onFulfilled, onRejected)
        .then(kategori_list => displayKategori(kategori_list))
        .catch(error => console.error("Error loading categories:", error));
    await fetch(makanan_path)
        .then(onFulfilled, onRejected)
        .then(makanan => setupMakanan(makanan))
        .catch(error => console.error("Error loading food items:", error));
})

function displayKategori(kategori_list) {
    const menu = document.getElementById("menu");
    kategori_list.forEach(kategori => {
        const kategoriElement = document.createElement("div");
        kategoriElement.classList.add("kategori");
        kategoriElement.id = kategori.label;
        kategoriElement.innerHTML = `<h1>${kategori.name}</h1>`;
        menu.appendChild(kategoriElement);
    });
}

function setupMakanan(makanan) {
    displayMakanan(makanan);
    addGetItemOnClick();
}

function displayMakanan(makanan) {
    makanan.forEach(item => {
        const kategori = document.getElementById(item.label);
        const foodItem = document.createElement("div");
        foodItem.classList.add("food_item")
        foodItem.innerHTML = `
            <img src="${item.imej}" alt="${item.nama}">
            <div class="food_info">
                <div class="name_tag">
                    <h2>${item.nama}</h2>
                    <sl-tag size="small" pill>${item.label + item.nombor}</sl-tag>
                </div>
                <div class="item_bottom">
                    <p><strong>Price: RM${item.harga}</strong></p>
                    <sl-icon-button class="get_item" name="plus-square"></sl-icon-button>
                </div>
            </div>
        `;
        kategori.appendChild(foodItem);
    });
}

function addGetItemOnClick() {
    const dialog = document.getElementById("item-dialog");
    const addItemButtons = document.getElementsByClassName("get_item");

    for (i = 0; i < addItemButtons.length; i++) {
        addItemButtons[i].addEventListener("click", () => dialog.show());
    }
}

const onFulfilled = (response) => {
    if (response.status !== 200 && !response.ok) {
      throw new Error(`[${response.status}] Unable to fetch resource`)
    }
    return response.json()
}
  
const onRejected = (err) => {
    console.error(err)
}