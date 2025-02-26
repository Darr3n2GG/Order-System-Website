import { eventBus } from "../../scripts/eventBus.js";
const url = "../../backend/receiveCheckout.php"

eventBus.addEventListener("checkout", event => {
    checkout(event.detail);
})

async function checkout(cart) {
    const formData = new FormData();

    formData.append("cart", JSON.stringify(cart))

    const response = await fetch(url, {
        method: "POST",
        body: formData
    });

    if (response.ok) {
        console.log("checkout!");
        console.log(response.json());
        window.location.href = "https://www.youtube.com/watch?v=xvFZjo5PgG0";
    } else {
        console.log("checkout failed");
    }
}