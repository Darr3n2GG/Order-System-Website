import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/ProdukAPI.php"

const editProdukDialog = document.querySelector(".edit_produk_dialog");

const tablePelanggan = new Tabulator("#table_menu", {
    ajaxURL: ApiUrl + "?" + new URLSearchParams({
        type: "data"
    }).toString(),
    ajaxConfig: { method: "GET" },
    ajaxRequestFunc: (url, config) => getTableData(url, config),
    printAsHtml: true,
    height: 510,
    rowHeight: 40,
    layout: "fitData",
    columns: [
        { title: "ID", field: "id", },
        { title: "Nama", field: "nama" },
        { title: "Kategori", field: "kategori_nama", },
        { title: "Harga (RM)", field: "harga" },
        {
            title: "Gambar",
            field: "gambar",
            hozAlign: "center",
            headerSort: false,
            formatter: function (cell) {
                const value = cell.getValue();

                return `<img class="gambar_produk" src="${value}"></img>`;
            }
        },
        {
            title: "",
            field: "update",
            hozAlign: "center",
            resizable: false,
            headerSort: false,
            formatter: function () {
                return '<sl-icon-button name="pencil"></sl-icon-button>';
            },
            cellClick: (e, cell) => showEditDialog(e, cell)
        },
        {
            title: "",
            field: "delete",
            hozAlign: "center",
            resizable: false,
            headerSort: false,
            formatter: function () {
                return '<sl-icon-button name="trash"></sl-icon-button>';
            },
            cellClick: (e, cell) => deletePelanggan(e, cell)
        },
    ],
});

async function getTableData(url, config) {
    try {
        const response = await fetch(url, config);
        const data = await FetchHelper.onFulfilled(response);
        if (data.details === undefined) {
            return [];
        } else {
            const tableData = data.details
            for (let row in tableData) {
                tableData[row]._editable = false;
            }
            return tableData;
        }
    } catch (error) {
        return FetchHelper.onRejected(error);
    }
}

function showEditDialog(e, cell) {
    const row = cell.getRow();
    const data = row.getData();

    document.getElementById("edit_produk_id").value = data.id;
    document.getElementById("edit_produk_nama").value = data.nama;
    document.getElementById("edit_produk_kategori").value = data.kategori;
    document.getElementById("edit_produk_harga").value = data.harga;
    document.getElementById("edit_produk_detail").value = data.detail;

    editProdukDialog.show()
}