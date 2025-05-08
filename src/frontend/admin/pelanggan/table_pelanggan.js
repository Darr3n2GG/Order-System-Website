// table_pelanggan.js
import TableManager from "../shared/TableManager.js";
import ViewModel from "./PelangganViewModel.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI.php";

const TahapEnum = {
    "1": "User",
    "2": "Admin"
};

// 🔸 Columns
const columns = [
    { title: "ID", field: "id" },
    { title: "Nama", field: "nama", width: 250 },
    { title: "Nombor Phone", field: "no_phone" },
    {
        title: "Tahap",
        field: "tahap",
        formatter: (cell) => TahapEnum[cell.getValue()] || cell.getValue()
    },
    {
        title: "",
        field: "update",
        hozAlign: "center",
        headerSort: false,
        formatter: () => '<sl-icon-button name="pencil"></sl-icon-button>',
        cellClick: (e, cell) => {
            pelangganTable.showEditDialog(cell.getData(), {
                id: "edit_id",
                nama: "edit_nama",
                no_phone: "edit_nombor_phone",
                tahap: "edit_tahap"
            });
        }
    },
    {
        title: "",
        field: "delete",
        hozAlign: "center",
        headerSort: false,
        formatter: () => '<sl-icon-button name="trash"></sl-icon-button>',
        cellClick: async (e, cell) => {
            const result = await ViewModel.deleteData(cell.getData().id);
            if (result.ok) {
                cell.getRow().delete();
                alert("Pelanggan dipadam.");
            } else {
                alert("Gagal memadam pelanggan.");
            }
        }
    }
];

// 🔸 Filters
const filters = {
    nama: document.getElementById("filter_nama"),
    no_phone: document.getElementById("filter_no_phone")
};

// 🔸 Initialize Table Manager
const pelangganTable = new TableManager({
    tableId: "#table_pelanggan",
    apiUrl: ApiUrl,
    viewModel: ViewModel,
    columns,
    filters
});

// Setup Tambah Form
const formConfig = {
    formElement: document.querySelector(".form_pelanggan"),
    formValidity: {
        tambah_nama: { condition: (v) => ViewModel.validateField("nama", v) },
        tambah_no_phone: { condition: (v) => ViewModel.validateField("no_phone", v) },
        tambah_password: { condition: (v) => ViewModel.validateField("password", v) },
        tambah_tahap: { condition: (v) => ViewModel.validateField("tahap", v) }
    }
};
pelangganTable.setupFormSubmit(formConfig);

// Setup Edit Form
const dialogConfig = {
    formValidity: {
        edit_id: { condition: () => "" },
        edit_nama: { condition: (v) => ViewModel.validateField("nama", v) },
        edit_nombor_phone: { condition: (v) => ViewModel.validateField("no_phone", v) },
        edit_tahap: { condition: (v) => ViewModel.validateField("tahap", v) }
    }
};
pelangganTable.setupEditDialog(dialogConfig);

// Setup CSV Import
pelangganTable.setupCSVImport();

// Call print method
document.getElementById("print_button").addEventListener("click", () => {
    pelangganTable.printReport();  // This will print the table, ignoring columns with empty titles
});

// 🔸 Sync Hidden Dropdown
document.getElementById("hidden_tambah_tahap").value = document.getElementById("tambah_tahap").value;
document.getElementById("tambah_tahap").addEventListener("sl-change", (event) => {
    document.getElementById("hidden_tambah_tahap").value = event.target.value;
});