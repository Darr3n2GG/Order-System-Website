import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI.php"

const editDialog = document.querySelector(".edit_dialog");

const tablePelanggan = new Tabulator("#table_pelanggan", {
    ajaxURL: ApiUrl,
    ajaxConfig: { method: "GET" },
    ajaxRequestFunc: (url, config) => getTableData(url, config),
    height: 510,
    rowHeight: 40,
    layout: "fitData",
    columns: [
        { title: "ID", field: "id", },
        { title: "Nama", field: "nama", width: 177.8 + 73 },
        { title: "Nombor Phone", field: "no_phone", },
        { title: "Tahap", field: "tahap" },
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

const tahap_enum = {
    user: "1",
    admin: "2"
}

function showEditDialog(e, cell) {
    const row = cell.getRow();
    const data = row.getData();

    document.getElementById("edit_nama").value = data.nama;
    document.getElementById("edit_nombor_phone").value = data.no_phone;
    document.getElementById("edit_tahap").value = tahap_enum[data.tahap];

    editDialog.show()
}

async function deletePelanggan(e, cell) {
    const row = cell.getRow();
    const id = row.getData().id;

    const url = ApiUrl + "?" + new URLSearchParams({
        id: id
    }).toString();

    try {
        const response = await fetch(url, { method: "DELETE" });
        const data = await FetchHelper.onFulfilled(response);

        if (response.ok) {
            row.delete();
        }
    } catch (error) {
        FetchHelper.onRejected(error);
    }

    alert("Pelanggan dengan ID : " + id + " sudah dipadamkan.")
}

function returnNoContent() {
    console.log("No content in table : table_pengguna.");
}