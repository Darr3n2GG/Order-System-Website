const kategori_path = "/Order-System-Website/src/backend/fetchKategori.php"
const makanan_path = "/Order-System-Website/src/backend/fetchMakanan.php"

document.addEventListener("DOMContentLoaded", async () => {
    await fetch(kategori_path)
        .then(onFulfilled, onRejected)
        .then(kategori_list => displayKategori(kategori_list))
        .catch(error => console.error("Error loading categories:", error));
    await fetch(makanan_path)
        .then(onFulfilled, onRejected)
        .then(makanan_list => setupMakanan(makanan_list))
        .catch(error => console.error("Error loading food items:", error));
})

function displayKategori(kategori_list) {
    const menu = document.getElementById("menu");
    kategori_list.forEach(kategori => {
        const kategoriElement = document.createElement("div");
        kategoriElement.classList.add("kategori");
        kategoriElement.id = kategori.label;
        kategoriElement.innerHTML = `<h1>${kategori.nama}</h1>`;
        menu.appendChild(kategoriElement);
    });
}

function setupMakanan(makanan_list) {
    console.log(makanan_list)
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
                <div class="name_tag">
                    <h2>${item.nama}</h2>
                    <sl-tag size="small" pill>${item.label + item.nombor}</sl-tag>
                </div>
                <div class="item_bottom">
                    <p><strong>Price: RM${item.harga}</strong></p>
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
    const addItemButtons = document.getElementsByClassName("dialog_button");
    for (let i = 0; i < addItemButtons.length; i++) {
        addItemButtons[i].addEventListener("click", () => {
            showDialog(makanan_list[i]);
        });
    }
}

function showDialog(makanan) {
    const dialog = document.getElementById("item-dialog");
    dialog.label = makanan.label + makanan.nombor + " : " + makanan.nama;
    dialog.querySelector(".dialog_image").src = makanan.gambar;
    dialog.querySelector(".dialog_price").innerHTML = "Price : RM" + makanan.harga;
    dialog.show();
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