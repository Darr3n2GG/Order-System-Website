const makanan_json = "/Order-System-Website/src/backend/fetchMakanan.php"

document.addEventListener("DOMContentLoaded", () => {
    fetch(makanan_json)
        .then(response => response.json())
        .then(data => displayMakanan(data))
        .catch(error => console.error("Error loading food items:", error));
});

function displayMakanan(data) {
    const menu = document.getElementById("menu");
    data.forEach(item => {
        const foodItem = document.createElement("div");
        foodItem.classList.add("food_item")
        foodItem.innerHTML = `
            <img src="${item.imej}" alt="${item.nama}">
            <div class="food_info">
                <div class="name_tag">
                    <h2>${item.nama}</h2>
                    <sl-tag size="small" pill>${item.kategori + item.nombor}</sl-tag>
                </div>
                <p><strong>Price: $${item.harga}</strong></p>
            </div>
        `;
        menu.appendChild(foodItem);
    });
}