import { eventBus } from "../../scripts/eventBus.js";
import fetchHelper from "../../scripts/fetchHelper.js";

const apiUrl = "../../backend/CheckoutAPI.php";
const redirectUrl = "../status/status.html";

eventBus.addEventListener("checkout", ({ detail }) => {
    checkout(detail);
})

function checkout(cart) {
    // FormData allows us to submit forms without creating a form in html
    const formData = new FormData();
    formData.append("cart", JSON.stringify(cart));

    fetch(apiUrl, {
        method: "POST",
        body: formData
    })
    .then(fetchHelper.onFulfilled)
    .then(response => {
        if (response.ok) {
            window.location.href = redirectUrl;
        }
    })
    .catch(fetchHelper.onRejected);
    // rickroll?
}