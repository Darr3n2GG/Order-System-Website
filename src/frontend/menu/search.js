import FetchHelper from "../../scripts/FetchHelper.js";

const searchBar = document.querySelector(".search_bar");

const startFetching = debounce(fetchFoodData, 1000)

searchBar.addEventListener("sl-input", () => {
    startFetching()
});

function debounce(callback, delay) {
    let timer
    return function() {
        clearTimeout(timer)
        timer = setTimeout(() => {
            callback();
        }, delay)
    }
}

function fetchFoodData() {
    const apiUrl = "../../backend/MakananAPI.php"
    const url = apiUrl + "?" + new URLSearchParams({
        keyword : searchBar.value
    }).toString()

    fetch(url)
        .then(FetchHelper.onFulfilled)
        .then(({ details }) => {
            renderFoodItems(details.items)
        })
        .catch(FetchHelper.onRejected);
}

function renderFoodItems(items) {
    const kategoriTitleList = document.getElementsByClassName("kategori_title");
    for (let i = 0; i < kategoriTitleList.length; i++) {
        const kategoriTitle = kategoriTitleList[i]
        const foodItemsHTML = createFoodItemsHTML(kategoriTitle.id);
        kategoriTitle.querySelector(".food_item_container").innerHTML = foodItemsHTML
        if (foodItemsHTML === "") {
            kategoriTitle.classList.add("hide")
        } else {
            kategoriTitle.classList.remove("hide")
        }
    }

    function createFoodItemsHTML(kategori) {
        let foodItemsHTML = ""
        for (let i in items) {
            if (items[i].kategori === kategori) {
                foodItemsHTML += items[i].html;
            }
        }
        return foodItemsHTML;
    }
}