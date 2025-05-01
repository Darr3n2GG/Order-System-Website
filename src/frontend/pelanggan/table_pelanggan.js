const ApiUrl = "/Order-System-Website/src/backend/api/PesananAPI.php";

const tableSenaraiPelanggan = new Tabulator("#table_senarai_pesanan", {
    layout: "fitData",
    height: 550,
    columns: [
        { title: "No.", field: "id", },
        { title: "Tarikh", field: "tarikh", },
        { title: "Status", field: "status" },
        { title: "Jumlah Harga (RM)", field: "jumlah_harga" },
        { title: "Cara", field: "cara" },
        // { title: "Nombor Meja", field: "no_meja" },
        {
            title: "",
            field: "view",
            hozAlign: "center",
            resizable: false,
            headerSort: false,
            formatter: function () {
                return '<sl-icon-button name="eye"></sl-icon-button>';
            },
            cellClick: (e, cell) => showViewDialog(e, cell)
        }
    ],
})