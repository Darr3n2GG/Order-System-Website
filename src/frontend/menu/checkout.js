import { eventBus } from "../../scripts/eventBus.js";
import fetchHelper from "../../scripts/fetchHelper.js";
const url = "../../backend/receiveCheckout.php"

eventBus.addEventListener("checkout", event => {
    checkout(event.detail);
})

function checkout(cart) {
    // FormData allows us to submit forms without creating a form in html
    const formData = new FormData();
    formData.append("cart", JSON.stringify(cart));

    fetch(url, {
        method: "POST",
        body: formData
    })
    .then(fetchHelper.onFulfilled)
    .catch(fetchHelper.onRejected);
}

function checkResponse(response) {
    if (response.ok) {
        console.log("checkout!");
        console.log(response);
        const msg = response.json()
        console.log(msg);
        // window.location.href = "https://www.youtube.com/watch?v=xvFZjo5PgG0";
    } else {
        console.log("checkout failed");
    }
}