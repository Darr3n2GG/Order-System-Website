import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI.php"


new Tabulator("#table_pelanggan", {
    ajaxURL: ApiUrl,
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
    columns: [
        { title: "ID", field: "id" },
        { title: "Nama", field: "nama" },
        { title: "Nombor Phone", field: "no_phone" },
        {
            title: "",
            field: "update",
            hozAlign: "center",
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
            headerSort: false,
            formatter: function () {
                return '<sl-icon-button name="trash"></sl-icon-button>';
            },
            cellClick: function (e, cell) {
            }
        },
    ],
});

function returnNoContent() {
    console.log("No content in table : table_pengguna.");
}