const kategori_path = "/Order-System-Website/src/backend/fetchKategori.php"
const makanan_path = "/Order-System-Website/src/backend/fetchMakanan.php"

document.addEventListener("DOMContentLoaded", () => {
    fetch(kategori_path)
        .then(onFulfilled, onRejected)
        .then(kategori_list => displayKategori(kategori_list))
        .catch(error => console.error("Error loading categories:", error));
    fetch(makanan_path)
        .then(onFulfilled, onRejected)
        .then(makanan => displayMakanan(makanan))
        .catch(error => console.error("Error loading food items:", error));
})

const onFulfilled = (response) => {
    if (response.status !== 200 && !response.ok) {
      throw new Error(`[${response.status}] Unable to fetch resource`)
    }
    return response.json()
}
  
const onRejected = (err) => {
    console.error(err)
}

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
                <p><strong>Price: RM${item.harga}</strong></p>
            </div>
        `;
        kategori.appendChild(foodItem);
    });
}