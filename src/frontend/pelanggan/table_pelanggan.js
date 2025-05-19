import FetchHelper from "../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PesananAPI.php";
const id_pelanggan = document.getElementById("table_senarai_pesanan").dataset.id_pelanggan

const timeDariFilter = document.getElementById("time_dari");
const timeHinggaFilter = document.getElementById("time_hingga");

let dariFilter = "";
let hinggaFilter = "";

const tableSenaraiPelanggan = new Tabulator("#table_senarai_pesanan", {
    ajaxURL: ApiUrl,
    ajaxConfig: { method: "GET" },
    ajaxRequestFunc: (url, config) => getTableData(url, config),
    layout: "fitData",
    height: 550,
    rowHeight: 40,
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
        let params = "";
        if (dariFilter !== "" && hinggaFilter !== "") {
            params = "?" + new URLSearchParams({
                range: "date",
                from: dariFilter,
                to: hinggaFilter,
                id_pelanggan: id_pelanggan
            }).toString();
        } else {
            params = "?" + new URLSearchParams({
                range: "*",
                id_pelanggan: id_pelanggan
            })
        }

        const response = await fetch(url + params, config);
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

timeDariFilter.addEventListener("input", (e) => {
    const dari = e.target.value;
    if (timeHinggaFilter.value === "") {
        timeHinggaFilter.value = getCurrentDate();
    }
    const hingga = timeHinggaFilter.value
    setTimeFilter(dari, hingga)
})

timeHinggaFilter.addEventListener("input", (e) => {
    const hingga = e.target.value;
    if (timeDariFilter.value === "") {
        timeDariFilter.value = getCurrentDate();
    }
    const dari = timeDariFilter.value;
    setTimeFilter(dari, hingga)
})

function setTimeFilter(from, to) {
    dariFilter = from;
    hinggaFilter = to
    tableSenaraiPelanggan.setData()
}

function getCurrentDate() {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-based
    const dd = String(today.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
}

function showViewDialog(e, cell) {
    const data = cell.getRow().getData();

}