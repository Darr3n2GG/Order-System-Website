import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI.php"


const tablePelanggan = new Tabulator("#table_pelanggan", {
    ajaxURL: ApiUrl,
    ajaxConfig: {
        method: "GET"
    },
    ajaxRequestFunc: async (url, config) => {
        try {
            const response = await fetch(url, config);
            const data = await FetchHelper.onFulfilled(response);
            if (data.details === undefined) {
                return [];
            }
            return data.details;
        } catch (error) {
            return FetchHelper.onRejected(error);
        }
    },
    height: 510,
    rowHeight: 40,
    layout: "fitData",
    resizableRows: false,
    columns: [
        { title: "ID", field: "id", resizable: false },
        { title: "Nama", field: "nama", width: 177.8 + 87, resizable: false },
        { title: "Nombor Phone", field: "no_phone", resizable: false },
        {
            title: "",
            field: "update",
            hozAlign: "center",
            resizable: false,
            headerSort: false,
            formatter: function () {
                return '<sl-icon-button name="pencil"></sl-icon-button>';
            },
            cellClick: function (e, cell) {
            }
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
            cellClick: deletePelanggan
        },
    ],
});

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