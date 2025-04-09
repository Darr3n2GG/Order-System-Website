import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PesananAPI.php?"


new Tabulator("#table_pesanan", {
    ajaxURL: ApiUrl + new URLSearchParams({
        range: "week"
    }).toString(),
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
    height: 205,
    rowHeight: 40,
    layout: "fitData",
    columns: [
        { title: "Nama", field: "nama", width: 150 },
        { title: "Tarikh", field: "tarikh" },
        { title: "Cara", field: "cara" },
        { title: "Nombor Meja", field: "no_meja" },
    ],
});