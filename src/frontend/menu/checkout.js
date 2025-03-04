import { eventBus } from "../../scripts/eventBus.js";
import fetchHelper from "../../scripts/fetchHelper.js";
const url = "../../backend/ReceiveCheckout.php"

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
    .then(response => {
        if (response.ok === true) {
            window.location.href = "../status/status.html";
        }
    })
    .catch(fetchHelper.onRejected);
    // rickroll?
}