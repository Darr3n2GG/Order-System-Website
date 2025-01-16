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
                    <h2>${item.name}</h2>
                    <p><strong>Price: $${item.price}</strong></p>
                `;
                menu.appendChild(foodItem);
            });
        })
        .catch(error => console.error("Error loading food items:", error));
});