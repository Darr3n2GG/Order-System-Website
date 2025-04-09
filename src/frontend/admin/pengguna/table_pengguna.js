import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = ""


new Tabulator("#table_pengguna", {
    ajaxURL: ApiUrl,
    ajaxRequestFunc: (url, config) => {
        return fetch(url, config)
            .then(FetchHelper.onFulfilled)
            .then(data => {
                return data.details;
            })
            .catch(FetchHelper.onRejected);
    },
    height: "auto",
    rowHeight: 40,
    layout: "fitData",
    columns: [
    ],
});