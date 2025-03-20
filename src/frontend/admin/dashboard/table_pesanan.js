import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/PesananAPI.php"


const table = new Tabulator("#table_pesanan", {
    ajaxURL: ApiUrl,
    ajaxRequestFunc: function (url, config) {
        return fetch(url, config)
            .then(FetchHelper.onFulfilled)
            .then(data => {
                return data.details;
            })
            .catch(FetchHelper.onRejected);
    },
    height: 205,
    rowHeight: 40,
    layout: "fitColumns",
    columns: [ //Define Table Columns
        { title: "Nama", field: "nama", width: 150 },
        { title: "Tarikh", field: "tarikh" },
        { title: "Cara", field: "cara" },
        { title: "Nombor Meja", field: "no_meja" },
    ],
});