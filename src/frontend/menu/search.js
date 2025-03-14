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
    console.log(url)

    fetch(url)
        .then(FetchHelper.onFulfilled)
        .then(({ details }) => {
            let menuHTML = "";
            for (let i = 0; i < details.items.length; i++) {
                menuHTML = menuHTML + details.items[i]
            }

            const menu = document.querySelector(".menu");
            menu.innerHTML = menuHTML
        })
        .catch(FetchHelper.onRejected);
}
  