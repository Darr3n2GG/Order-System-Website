import FetchHelper from "../../../scripts/FetchHelper.js";
import FormValidator from "../../../scripts/FormValidator.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI.php"

const editDialog = document.querySelector(".edit_dialog");
const editForm = editDialog.querySelector(".edit_form");

const tablePelanggan = new Tabulator("#table_pelanggan", {
    ajaxURL: ApiUrl,
    ajaxConfig: { method: "GET" },
    ajaxRequestFunc: (url, config) => getTableData(url, config),
    height: 510,
    rowHeight: 40,
    layout: "fitData",
    columns: [
        { title: "ID", field: "id", },
        { title: "Nama", field: "nama", width: 177.8 + 73 },
        { title: "Nombor Phone", field: "no_phone", },
        { title: "Tahap", field: "tahap" },
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
            cellClick: (e, cell) => deletePelanggan(e, cell)
        },
    ],
});

async function getTableData(url, config) {
    try {
        const response = await fetch(url, config);
        const data = await FetchHelper.onFulfilled(response);
        if (data.details === undefined) {
            return [];
        } else {
            const tableData = data.details
            for (let row in tableData) {
                tableData[row]._editable = false;
            }
            return tableData;
        }
    } catch (error) {
        return FetchHelper.onRejected(error);
    }
}

const tahap_enum = {
    user: "1",
    admin: "2"
}

function showEditDialog(e, cell) {
    const row = cell.getRow();
    const data = row.getData();

    document.getElementById("edit_id").value = data.id;
    document.getElementById("edit_nama").value = data.nama;
    document.getElementById("edit_nombor_phone").value = data.no_phone;
    document.getElementById("edit_tahap").value = tahap_enum[data.tahap];

    editDialog.show()
}

const editFormValidity = {
    edit_id: { condition: (value) => { return "" } },
    edit_nama: { condition: (value) => handleNamaValidation(value) },
    edit_nombor_phone: { condition: (value) => handlePhoneValidation(value) },
    edit_tahap: { condition: (value) => handleTahapValidation(value) }
};

editDialog.querySelector(".edit_button").addEventListener("click", () => {
    if (FormValidator.validateForm(editFormValidity)) {
        const data = new FormData(editForm);
        patchPelangganData(data, "Data pelanggan sudah ditukar.");
    }
})

async function patchPelangganData(formData, message) {
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

editForm.addEventListener("input", (event) => {
    const id = event.target.id;
    FormValidator.validateField(editFormValidity, id);
})

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

function handlePhoneValidation(value) {
    if (value === "") {
        return "Input nombor phone kosong.";
    } else if (!isValidPhoneNumber(value)) {
        return "Input password tidak sah.";
    } else {
        return "";
    }
}

function handleTahapValidation(value) {
    if (value === "") {
        return "Field tahap kosong.";
    } else {
        return "";
    }
}

function isValidCharacters(value) {
    const whitelistPattern = /^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?`~]+$/;
    return whitelistPattern.test(value);
}

function isValidPhoneNumber(value) {
    const whitelistPattern = /^(\+?6?01)[02-46-9]-*[0-9]{7}$|^(\+?6?01)[1]-*[0-9]{8}$/;
    return whitelistPattern.test(value);
}