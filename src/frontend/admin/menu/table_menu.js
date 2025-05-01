import FetchHelper from "../../../scripts/FetchHelper.js";
import FormValidator from "../../../scripts/FormValidator.js";

const ApiUrl = "/Order-System-Website/src/backend/api/ProdukAPI.php"

const editProdukDialog = document.querySelector(".edit_produk_dialog");
const editForm = editProdukDialog.querySelector(".edit_produk_form");

// === Global Filter Variable ===
let filterNama = "";

const tablePelanggan = new Tabulator("#table_menu", {
    ajaxURL: ApiUrl + "?" + new URLSearchParams({
        type: "data"
    }).toString(),
    ajaxConfig: { method: "GET" },
    ajaxRequestFunc: (url, config) => getTableData(url, config),
    printAsHtml: true,
    height: 510,
    rowHeight: 40,
    layout: "fitData",
    columns: [
        { title: "ID", field: "id", },
        { title: "Nama", field: "nama" },
        { title: "Kategori", field: "kategori_nama", },
        { title: "Harga (RM)", field: "harga" },
        {
            title: "Gambar",
            field: "gambar",
            hozAlign: "center",
            headerSort: false,
            formatter: function (cell) {
                const value = cell.getValue();

                return `<img class="gambar_produk" src="${value}"></img>`;
            }
        },
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
            cellClick: (e, cell) => deleteProduk(e, cell)
        },
    ],
});

// === Print Button Logic ===
document.getElementById("print_button").addEventListener("click", () => {
    const rows = tablePelanggan.getRows(); // All currently displayed (filtered) rows
    const data = rows.map(row => row.getData());

    const printWindow = window.open('', '', 'width=800,height=600');
    if (!printWindow) return;

    const tableHTML = `
        <html>
        <head>
            <title>Cetak Menu</title>
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #333; padding: 8px; text-align: left; vertical-align: top; }
                th { background-color: #f2f2f2; }
                body { font-family: sans-serif; padding: 20px; }
                img { max-width: 100px; height: auto; }
            </style>
        </head>
        <body>
            <h2>Senarai Menu</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th>Gambar</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.map(row => `
                        <tr>
                            <td>${row.id}</td>
                            <td>${row.nama}</td>
                            <td>${row.harga}</td>
                            <td>${row.kategori_nama}</td>
                            <td><img src="${row.gambar}" alt="Gambar Produk"></td>
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

// === Filtering by Nama ===
document.getElementById("filter_nama").addEventListener("sl-change", (e) => {
    filterNama = e.target.value.trim().toLowerCase();
    // Update the URL to include type=data and the filterNama parameter if provided
    const filteredApiUrl = `${ApiUrl}?type=data&keyword=${encodeURIComponent(filterNama)}`;

    // Triggers reload + filtering
    tablePelanggan.setData(filteredApiUrl);
});

document.querySelector(".form_produk").addEventListener("submit", async (e) => {
    e.preventDefault(); // Prevent the form from submitting normally

    const nama = document.getElementById("tambah_produk_nama").value;
    const kategori = document.getElementById("tambah_produk_kategori").value;
    const harga = document.getElementById("tambah_produk_harga").value;
    const detail = document.getElementById("tambah_produk_detail").value;

    // Check for empty fields
    if (!nama || !kategori || !harga || !detail) {
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
            body: JSON.stringify({ nama, kategori, harga, detail })
        });

        const result = await FetchHelper.onFulfilled(response);

        if (result.ok) {
            alert("Produk berjaya ditambah!");
            tablePelanggan.setData(ApiUrl + "?type=data"); // Refresh table
            // Optionally, clear the form
            document.querySelector(".form_produk").reset();
        } else {
            alert("Gagal menambah produk.");
        }
    } catch (error) {
        FetchHelper.onRejected(error);
    }
});


async function getTableData(url, config) {
    try {
        const response = await fetch(url, config);
        const data = await FetchHelper.onFulfilled(response);
        if (data.details === undefined) return [];

        return data.details.filter(item => {
            const matchNama = item.nama.toLowerCase().includes(filterNama);
            return matchNama;
        });
    } catch (error) {
        return FetchHelper.onRejected(error);
    }
}

function showEditDialog(e, cell) {
    const row = cell.getRow();
    const data = row.getData();

    document.getElementById("edit_produk_id").value = data.id;
    document.getElementById("edit_produk_nama").value = data.nama;
    document.getElementById("edit_produk_kategori").value = data.kategori;
    document.getElementById("edit_produk_harga").value = data.harga;
    document.getElementById("edit_produk_detail").value = data.detail;

    editProdukDialog.show()
}

const editFormValidity = {
    //    edit_produk_id: { condition: (value) => { return "" } },
    //    edit_produk_nama: { condition: (value) => handleNamaValidation(value) },
    //    edit_nombor_phone: { condition: (value) => handlePhoneValidation(value) },
    //    edit_tahap: { condition: (value) => handleTahapValidation(value) }
};

editProdukDialog.querySelector(".edit_button").addEventListener("click", () => {
    if (FormValidator.validateForm(editFormValidity)) {
        const data = new FormData(editForm);
        patchProdukData(data, "Data produk sudah diedit.");
    }
})

async function patchProdukData(formData, message) {
    try {
        const data = Object.fromEntries(formData);

        const response = await fetch(ApiUrl, {
            method: "PATCH",
            body: JSON.stringify(data)
        })

        const responseMessage = await FetchHelper.onFulfilled(response)
        if (responseMessage.ok) {
            alert(message);
            setTimeout(location.reload(), 500)
        } else {
            console.error(responseMessage.message);
        }
    } catch (error) {
        FetchHelper.onRejected(error);
    }
}

async function deleteProduk(e, cell) {
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

    alert("Produk dengan ID : " + id + " sudah dipadamkan.")
}

tablePelanggan.on("tableBuilt", () => {
    // tablePelanggan.print();
});

function handleNamaValidation(value) {
    if (value === "") {
        return "Field nama kosong.";
    } else if (!isValidCharacters(value)) {
        return "Field nama terdapat character invalid.";
    } else if (value.length >= 100) {
        return "Field nama mesti kurang daripada 100.";
    } else {
        return "";
    }
}

function isValidCharacters(value) {
    const whitelistPattern = /^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?`~]+$/;
    return whitelistPattern.test(value);
}

