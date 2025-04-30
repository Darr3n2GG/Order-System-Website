const logKeluarButton = document.getElementById("log_keluar_button");

logKeluarButton.addEventListener("click", async () => {
    await fetch("/Order-System-Website/src/backend/api/LogKeluar.php");
    location.reload();
})