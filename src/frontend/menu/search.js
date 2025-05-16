import FetchHelper from "../../scripts/FetchHelper.js";

const apiUrl = "/Order-System-Website/src/backend/api/ProdukAPI2.php";
const searchBar = document.querySelector(".search_bar");

const startFetching = debounce(fetchFoodData, 1000)

searchBar.addEventListener("sl-change", () => {
    fetchFoodData()
})

// TODO : change mode based on action
// searchBar.addEventListener("sl-input", () => {
//     startFetching()
// });

function debounce(callback, delay) {
    let timer
    return function () {
        clearTimeout(timer)
        timer = setTimeout(() => {
            callback();
        }, delay)
    }
}

function fetchFoodData() {
    const url = apiUrl + "?" + new URLSearchParams({
        type: "html",
        nama: searchBar.value
    }).toString()

    fetch(url)
        .then(FetchHelper.onFulfilled)
        .then(({ details }) => {
            console.log(details);
            renderFoodItems(details)
        })
        .catch(FetchHelper.onRejected);
}

function renderFoodItems(items) {
    let emptyKategoriHTMLCount = 0

    const kategoriTitleList = document.getElementsByClassName("kategori_title");
    for (let i = 0; i < kategoriTitleList.length; i++) {
        const kategoriTitle = kategoriTitleList[i]
        const foodItemsHTML = createFoodItemsHTML(kategoriTitle.id);
        kategoriTitle.querySelector(".food_item_container").innerHTML = foodItemsHTML
        if (foodItemsHTML === "") {
            kategoriTitle.classList.add("hide")
            emptyKategoriHTMLCount += 1
        } else {
            kategoriTitle.classList.remove("hide")
        }
    }

    const menuEmpty = document.querySelector(".menu_empty")
    if (emptyKategoriHTMLCount === kategoriTitleList.length) {
        menuEmpty.classList.remove("hide")
    } else {
        menuEmpty.classList.add("hide")
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