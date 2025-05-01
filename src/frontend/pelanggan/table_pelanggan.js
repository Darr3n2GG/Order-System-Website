import FetchHelper from "../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PesananAPI.php";
const id_pelanggan = document.getElementById("table_senarai_pesanan").dataset.id_pelanggan

const tableSenaraiPelanggan = new Tabulator("#table_senarai_pesanan", {
    ajaxURL: ApiUrl + "?" + new URLSearchParams({
        range: "*",
        id_pelanggan: id_pelanggan
    }),
    ajaxConfig: { method: "GET" },
    ajaxRequestFunc: (url, config) => getTableData(url, config),
    layout: "fitData",
    height: 550,
    columns: [
        { title: "No.", formatter: "rownum", width: 60 },
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

async function getTableData(url, config) {
    try {
        const response = await fetch(url, config);
        const data = await FetchHelper.onFulfilled(response);
        if (data.details === undefined) {
            return [];
        } else {
            const tableData = data.details
            return tableData;
        }
    } catch (error) {
        return FetchHelper.onRejected(error);
    }
}