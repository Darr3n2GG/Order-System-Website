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
            const kategoriTitleList = document.getElementsByClassName("kategori_title");
            for (let i = 0; i < kategoriTitleList.length; i++) {
                const kategoriTitle = kategoriTitleList[i]
                const kategoriItems = details.items.find(item => item.kategori === kategoriTitle.id)
                console.log(kategoriItems)
                let kategoriHTML = ""
                for (let i = 0; i < kategoriItems.length; i++) {
                    kategoriHTML += kategoriItems[i].html;
                }
                kategoriTitle.innerHTML = kategoriHTML
            }
        })
        .catch(FetchHelper.onRejected);
}  