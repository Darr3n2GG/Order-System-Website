import FetchHelper from "../../../scripts/FetchHelper.js";


const ApiUrl = "/Order-System-Website/src/backend/api/KategoriAPI.php"

const editKategoriDialog = document.querySelector(".edit_kategori_dialog");

const tablePelanggan = new Tabulator("#table_kategori", {
    ajaxURL: ApiUrl,
    ajaxConfig: { method: "GET" },
    ajaxRequestFunc: (url, config) => getTableData(url, config),
    height: 510,
    rowHeight: 40,
    layout: "fitData",
    columns: [
        { title: "ID", field: "id", },
        { title: "Label", field: "label" },
        { title: "Nama", field: "nama", },
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

    document.getElementById("edit_kategori_id").value = data.id;
    document.getElementById("edit_kategori_label").value = data.label;
    document.getElementById("edit_kategori_nama").value = data.nama;

    editKategoriDialog.show()
}

