import { eventBus } from "../../scripts/EventBus.js";
import FetchHelper from "../../scripts/FetchHelper.js";

const apiUrl = "/Order-System-Website/src/backend/api/CheckoutAPI.php";
const redirectUrl = "../status/status.html";

eventBus.addEventListener("checkout", ({ detail }) => {
    const cart = detail;
    checkout(cart);
})

function checkout(cart) {
    // FormData allows us to submit forms without creating a form in html
    const formData = new FormData();
    formData.append("cart", JSON.stringify(cart));

    fetch(apiUrl, {
        method: "POST",
        body: formData
    })
        .then(FetchHelper.onFulfilled)
        .then(response => {
            if (response.ok) {
                showViewDialog(response.details.id)
            }
        })
        .catch(FetchHelper.onRejected);
}

function showViewDialog(id) {
    const url = '../receipt/receipt.php?' + new URLSearchParams({ id: id }).toString();

    // Set iframe src and show modal
    document.getElementById('receiptFrame').src = url;
    document.getElementById('receiptModal').style.display = 'block';
}