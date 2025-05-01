const logKeluarButton = document.getElementById("log_keluar_button");

logKeluarButton.addEventListener("click", async () => {
    alert("Sedang Log Keluar...")
    await fetch("/Order-System-Website/src/backend/api/LogKeluar.php");
    window.location.href = "/Order-System-Website/src/frontend/menu/menu.php";
})