import FetchHelper from "../../../scripts/FetchHelper.js";
import FormValidator from "../../../scripts/FormValidator.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PesananAPI.php"

const editDialog = document.querySelector(".edit_dialog");
const editForm = editDialog.querySelector(".edit_form");

let filterPelanggan = "";
let filterStatus = "";

const tablePesanan = new Tabulator("#table_pesanan", {
    ajaxURL: ApiUrl,
    ajaxConfig: { method: "GET" },
    ajaxRequestFunc: (url, config) => getTableData(url, config),
    height: 510,
    rowHeight: 40,
    layout: "fitData",
    columns: [
        { title: "ID", field: "id" },
        { title: "Pelanggan", field: "nama", width: 100 },
        { title: "Tarikh", field: "tarikh" },
        { title: "Status", field: "status" },
        { title: "Cara", field: "cara" },
        { title: "No Meja", field: "no_meja" },
        {
            title: "",
            field: "update",
            hozAlign: "center",
            resizable: false,
            headerSort: false,
            formatter: () => '<sl-icon-button name="pencil"></sl-icon-button>',
            cellClick: (e, cell) => showEditDialog(e, cell)
        },
        {
            title: "",
            field: "delete",
            hozAlign: "center",
            resizable: false,
            headerSort: false,
            formatter: () => '<sl-icon-button name="trash"></sl-icon-button>',
            cellClick: (e, cell) => deletePesanan(e, cell)
        },
    ],
});

document.getElementById("print_button").addEventListener("click", () => {
    const data = tablePesanan.getData();
    const printWindow = window.open('', '', 'width=800,height=600');
    if (!printWindow) return;

    const tableHTML = `
        <html>
        <head>
            <title>Cetak Pesanan</title>
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #333; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                body { font-family: sans-serif; padding: 20px; }
            </style>
        </head>
        <body>
            <h2>Senarai Pesanan</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Tarikh</th>
                        <th>Status</th>
                        <th>Cara</th>
                        <th>No Meja</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.map(row => `
                        <tr>
                            <td>${row.id}</td>
                            <td>${row.nama}</td>
                            <td>${row.tarikh}</td>
                            <td>${row.status}</td>
                            <td>${row.cara}</td>
                            <td>${row.no_meja}</td>
                        </tr>
                    `).join("")}
                </tbody>
            </table>
        </body>
        </html>
    `;
    printWindow.document.write(tableHTML);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
});

document.getElementById("filter_id_pelanggan").addEventListener("input", (e) => {
    filterPelanggan = e.target.value.trim().toLowerCase();
    tablePesanan.setData(ApiUrl);
});

document.querySelector(".form_pesanan").addEventListener("submit", async (e) => {
    e.preventDefault(); // Prevent the form from submitting normally

    const idPelanggan = parseInt(document.getElementById("tambah_id_pelanggan").value, 10); // integer
    const tarikh = document.getElementById("tambah_tarikh").value;                          // string (YYYY-MM-DD)
    const status = document.getElementById("tambah_status").value.trim();                   // string
    const cara = document.getElementById("tambah_cara").value.trim();                       // string
    const meja = parseInt(document.getElementById("tambah_meja").value, 10);                // integer

    // Check for empty fields
    if (!idPelanggan || !tarikh || !status || !cara || !meja) {
        alert("Sila isi semua ruangan wajib.");
        return; // Stop submission
    }
    
    try {
        // Send data to the server via POST
        const response = await fetch(ApiUrl + "?" + new URLSearchParams({
            type: "insert"
        }), {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ idPelanggan, tarikh, status, cara, meja })
        });

        const result = await FetchHelper.onFulfilled(response);

        if (result.ok) {
            alert("Pesanan berjaya ditambah!");
            tablePesanan.setData(ApiUrl + "?filter=y"); // Refresh table
            // Optionally, clear the form
            document.querySelector(".form_pesanan").reset();
        } else {
            alert("Gagal menambah pesanan.");
        }
    } catch (error) {
        FetchHelper.onRejected(error);
    }
});

async function getTableData(url, config) {
    // Append the filter parameters to the URL for server-side filtering
    const filteredUrl = `${url}?filter=y&pelanggan=${encodeURIComponent(filterPelanggan)}`;
    try {
        const response = await fetch(filteredUrl, config);
        const data = await FetchHelper.onFulfilled(response);
        if (!data.details) return [];

        return data.details; // Server will return the filtered results
    } catch (error) {
        return FetchHelper.onRejected(error);
    }
}

function showEditDialog(e, cell) {
    const data = cell.getRow().getData();
    document.getElementById("edit_id_pesanan").value = data.id;
    document.getElementById("edit_tarikh").value = data.tarikh;
    document.getElementById("edit_status").value = 1;
    document.getElementById("edit_cara").value = data.cara;
    document.getElementById("edit_meja").value = data.no_meja;

    editDialog.show();
}

const editFormValidity = {
    edit_tarikh: { condition: (value) => value === "" ? "Field tarikh kosong." : "" }
//    edit_id: { condition: () => "" },
//    edit_pelanggan: { condition: (value) => value === "" ? "Field pelanggan kosong." : "" },
//    edit_tarikh: { condition: (value) => value === "" ? "Field tarikh kosong." : "" },
//    edit_status: { condition: (value) => value === "" ? "Field status kosong." : "" },
};

editDialog.querySelector(".edit_button").addEventListener("click", () => {
    if (FormValidator.validateForm(editFormValidity)) {
        const formData = new FormData(editForm);
        patchPesananData(formData, "Pesanan berjaya dikemaskini.");
    }
});

async function patchPesananData(formData, message) {
    try {
        const data = Object.fromEntries(formData);
        console.log(data);
        const response = await fetch(ApiUrl, {
            method: "PATCH",
            body: JSON.stringify(data),
        });

        const result = await FetchHelper.onFulfilled(response);
        if (result.ok) {
            alert(message);
            setTimeout(location.reload(), 500);
        } else {
            console.error(result.message);
        }
    } catch (error) {
        FetchHelper.onRejected(error);
    }
}

editForm.addEventListener("input", (event) => {
    FormValidator.validateField(editFormValidity, event.target.id);
});

async function deletePesanan(e, cell) {
    const row = cell.getRow();
    const id = row.getData().id;

    const url = ApiUrl + "?" + new URLSearchParams({ id }).toString();

    try {
        const response = await fetch(url, { method: "DELETE" });
        const result = await FetchHelper.onFulfilled(response);
        if (response.ok) row.delete();
    } catch (error) {
        FetchHelper.onRejected(error);
    }

    alert("Pesanan dengan ID: " + id + " sudah dipadamkan.");
}
