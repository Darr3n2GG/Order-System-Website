const spinbox = document.querySelector(".spinbox");

document.addEventListener("click", (event) => {
    // composedPath gets the full event path including shadow DOM elements
    const path = event.composedPath();

    // Find the <sl-button> in the event path
    const button = path.find(element =>
        element instanceof HTMLElement &&
        element.tagName === "SL-BUTTON"
    );

    if (!button) {
        return;
    } else if (button.classList.contains('spinbox_increment')) {
        button.previousElementSibling.value++
        emitInputEvent(button)
    } else if (button.classList.contains('spinbox_decrement')) {
        button.nextElementSibling.value--
        emitInputEvent(button)
    }
});

function emitInputEvent(button) {
    button.dispatchEvent(new CustomEvent("sl-change", {
        bubbles: true,
        composed: true,
    }));
}