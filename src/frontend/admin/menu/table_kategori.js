import FetchHelper from "../../../scripts/FetchHelper.js";
import FormValidator from "../../../scripts/FormValidator.js";

const ApiUrl = "/Order-System-Website/src/backend/api/KategoriAPI.php"

const editKategoriDialog = document.querySelector(".edit_kategori_dialog");
const editForm = document.querySelector(".edit_kategori_form");

const tablePelanggan = new Tabulator("#table_kategori", {
    ajaxURL: ApiUrl,
    ajaxConfig: { method: "GET" },
    ajaxRequestFunc: (url, config) => getTableData(url, config),
    height: 510,
    rowHeight: 40,
    layout: "fitData",
    columns: [
        { title: "ID", field: "id", },
        { title: "Label", field: "label" },
        { title: "Nama", field: "nama", width: "65%" },
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
            cellClick: (e, cell) => deleteKategori(e, cell)
        },
    ],
});

document.querySelector(".form_kategori").addEventListener("submit", async (e) => {
    e.preventDefault(); // Prevent the form from submitting normally
    
    const nama = document.getElementById("tambah_kategori_nama").value;
    const label = document.getElementById("tambah_kategori_label").value;

    // Check for empty fields
    if (!nama || !label) {
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
            body: JSON.stringify({ nama, label })
        });

        const result = await FetchHelper.onFulfilled(response);

        if (result.ok) {
            alert("Kategori berjaya ditambah!");
            tablePelanggan.setData(ApiUrl + "?type=data"); // Refresh table
            // Optionally, clear the form
            document.querySelector(".form_kategori").reset();
        } else {
            alert("Gagal menambah kategori.");
        }
    } catch (error) {
        FetchHelper.onRejected(error);
    }
});

async function getTableData(url, config) {
    try {
        const response = await fetch(url, config);
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

function showEditDialog(e, cell) {
    const row = cell.getRow();
    const data = row.getData();

    document.getElementById("edit_kategori_id").value = data.id;
    document.getElementById("edit_kategori_label").value = data.label;
    document.getElementById("edit_kategori_nama").value = data.nama;

    editKategoriDialog.show()
}

const editFormValidity = {
//    edit_produk_id: { condition: (value) => { return "" } },
//    edit_produk_nama: { condition: (value) => handleNamaValidation(value) },
//    edit_nombor_phone: { condition: (value) => handlePhoneValidation(value) },
//    edit_tahap: { condition: (value) => handleTahapValidation(value) }
};

editKategoriDialog.querySelector(".edit_button").addEventListener("click", () => {
    if (FormValidator.validateForm(editFormValidity)) {
        const data = new FormData(editForm);
        patchKategoriData(data, "Data ketegori sudah diedit.");
    }
})

async function patchKategoriData(formData, message) {
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

async function deleteKategori(e, cell) {
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
            alert("Kategori dengan ID : " + id + " sudah dipadamkan.")
        } else {
            alert("Kategori dengan ID : " + id + " tidak dapat dipadamkan.")
        }
    } catch (error) {
        FetchHelper.onRejected(error);
        alert("Kategori tidak dapat dipadamkan.")
    }

}

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

