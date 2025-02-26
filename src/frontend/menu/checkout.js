import { eventBus } from "../../scripts/eventBus.js";
const url = "../../backend/receiveCheckout.php"

eventBus.addEventListener("checkout", event => {
    checkout(event.detail);
})

async function checkout(cart) {
    // FormData allows us to submit forms without creating a form in html
    const formData = new FormData();
    formData.append("cart", JSON.stringify(cart));

    const response = await fetch(url, {
        method: "POST",
        body: formData
    });

    checkResponse(response.ok)
}

function checkResponse(ok) {
    if (ok) {
        console.log("checkout!");
        console.log(response.json());
        window.location.href = "https://www.youtube.com/watch?v=xvFZjo5PgG0";
    } else {
        console.log("checkout failed");
    }
}