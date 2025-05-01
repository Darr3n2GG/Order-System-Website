import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PesananAPI.php"


new Tabulator("#table_pesanan", {
    ajaxURL: ApiUrl + "?" + new URLSearchParams({
        range: "week"
    }).toString(),
    ajaxConfig: {
        method: "GET"
    },
    ajaxRequestFunc: async (url, config) => {
        try {
            const response = await fetch(url, config);
            const data = await FetchHelper.onFulfilled(response);
            if (data.details === undefined) {
                returnNoContent();
                return [];
            }
            return data.details;
        } catch (error) {
            return FetchHelper.onRejected(error);
        }
    },
    height: 205,
    rowHeight: 40,
    layout: "fitData",
    columns: [
        { title: "Nama", field: "nama" },
        { title: "Tarikh", field: "tarikh" },
        { title: "Cara", field: "cara" },
        { title: "Nombor Meja", field: "no_meja" },
        { title: "Jumlah Harga (RM)", field: "jumlah_harga" },
    ],
});

function returnNoContent() {
    console.log("No content in table : table_pesanan.");
}