const apiUrl = "/Order-System-Website/src/backend/MakananAPI.php";

const categoryDropdown = document.querySelector(".category_dropdown");
const closeReceiptButton = document.getElementById("close_receipt_button");
const cetakButton = document.getElementById('cetak_button');


categoryDropdown.addEventListener('sl-select', ({ detail }) => {
    const selectedKategori = detail.item;
    document.getElementById(selectedKategori.value).scrollIntoView();
});

closeReceiptButton.addEventListener("click", function () {
    closeReceiptDialog();
});

cetakButton.addEventListener("click", function () {
    printReceipt();
})

function closeReceiptDialog() {
    location.reload();
}

function printReceipt() {
    const receiptIFrame = document.getElementById("receiptFrame")

    if (receiptIFrame && receiptIFrame.contentWindow) {
        receiptIFrame.contentWindow.focus();
        receiptIFrame.contentWindow.print();
    } else {
        console.error("Iframe not found or inaccessible.");
    }
}