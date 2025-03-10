const adminRedirectUrl = "../admin/main/admin-main.php"
const adminButton = document.querySelector(".admin_button");

adminButton.addEventListener("click", () => {
    window.location.href = adminRedirectUrl
});