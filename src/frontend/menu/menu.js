const foodItemsPath = "/Order-System-Website/src/foodItems.json"

document.addEventListener("DOMContentLoaded", () => {
    fetch(foodItemsPath)
        .then(response => response.json())
        .then(data => {
            const menu = document.getElementById("menu");
            data.forEach(item => {
                const foodItem = document.createElement("div");
                foodItem.classList.add("food_item")
                foodItem.innerHTML = `
                    <img src="${item.image}" alt="${item.name}">
                    <div class="food_info">
                        <div class="name_tag">
                            <h2>${item.name}</h2>
                            <sl-tag size="small" pill>A1</sl-tag>
                        </div>
                        <p><strong>Price: $${item.price}</strong></p>
                    </div>
                `;
                menu.appendChild(foodItem);
            });
        })
        .catch(error => console.error("Error loading food items:", error));
});