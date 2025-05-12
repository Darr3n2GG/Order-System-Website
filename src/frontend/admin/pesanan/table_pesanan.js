// table_pelanggan.js
import TableManager from "../shared/TableManager.js";
import ViewModel from "./PesananViewModel.js";
import PelangganViewModel from "../Pelanggan/PelangganViewModel.js";
import DropdownManager from "../shared/DropdownManager.js";

// Constant Enum (not from database)
const CaraEnum = {
    "1": "dine-in",
    "2": "take-away"
};

// Define Columns of Tabulator table
const columns = [
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
        headerSort: false,
        formatter: () => '<sl-icon-button name="pencil"></sl-icon-button>',
        cellClick: (e, cell) => {
            // show Edit dialog with the data from getData
            // data field : edit field id
            pesananTable.showEditDialog(cell.getData(), {
                id: "edit_id_pesanan",
                tarikh: "edit_tarikh",
                id_pelanggan: "edit_id_pelanggan",
                id_status: "edit_id_status",
                cara: "edit_cara",
                no_meja: "edit_no_meja"
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
                alert(`${ViewModel.resourceName} dengan ID: ${cell.getData().id} sudah dipadamkan.`);
            } else {
                alert(`${ViewModel.resourceName} dengan ID: ${cell.getData().id} gagal dipadamkan.`);
            }
        }
    }
];

// 🔸 Filters
const filters = {
    // database field to filter: element id of filter input box
    nama: document.getElementById("filter_id_pelanggan")
};

// 🔸 Initialize Table Manager
const pesananTable = new TableManager({
    tableId: "#table_pesanan",
    viewModel: ViewModel,
    columns,
    filters
});

// Setup Tambah Form
const formConfig = {
    formElement: document.querySelector(".form_pesanan"),
    formValidity: {
        // fields to validate in tambah form
        // element id: database field
        tambah_id_pelanggan: { condition: (v) => ViewModel.validateField("id_pelanggan", v) },
        tambah_tarikh: { condition: (v) => ViewModel.validateField("tarikh", v) },
        tambah_id_status: { condition: (v) => ViewModel.validateField("status", v) },
        tambah_cara: { condition: (v) => ViewModel.validateField("cara", v) },
        tambah_no_meja: { condition: (v) => ViewModel.validateField("no_meja", v) }
    }
};
pesananTable.setupFormSubmit(formConfig);

// Setup Edit Form
const dialogConfig = {
    formValidity: {
        // fields to validate in edit dialog
        // element id: database field
        edit_id_pelanggan: { condition: (v) => ViewModel.validateField("id_pelanggan", v) },
        edit_tarikh: { condition: (v) => ViewModel.validateField("tarikh", v) },
        edit_id_status: { condition: (v) => ViewModel.validateField("id_status", v) },
        edit_cara: { condition: (v) => ViewModel.validateField("cara", v) },
        edit_no_meja: { condition: (v) => ViewModel.validateField("no_meja", v) }
    }
};
pesananTable.setupEditDialog(dialogConfig);

// Setup CSV Import
pesananTable.setupCSVImport();

// Call print method
document.getElementById("print_button").addEventListener("click", () => {
    pesananTable.printReport();  // This will print the table, ignoring columns with empty titles
});

// Drop down lists
document.addEventListener('DOMContentLoaded', async () => {
    const pelangganList = await PelangganViewModel.getData(); // already JSON
    await DropdownManager.populateDropdown(
        document.getElementById('tambah_id_pelanggan'),
        pelangganList,
        'id',
        'nama'
    );
    DropdownManager.setupHiddenFieldBinding('tambah_id_pelanggan', 'hidden_tambah_id_pelanggan');

    const statusList = await ViewModel.getStatus(); 
    await DropdownManager.populateDropdown(
        document.getElementById('tambah_id_status'),
        statusList,
        'id',
        'status'
    );
    DropdownManager.setupHiddenFieldBinding('tambah_id_status', 'hidden_tambah_id_status');

    await DropdownManager.populateDropdown(
        document.getElementById('edit_id_status'),
        statusList,
        'id',
        'status'
    );

    const mejaList = await ViewModel.getMeja(); // already JSON
    await DropdownManager.populateDropdown(
        document.getElementById('tambah_no_meja'),
        mejaList,
        'no_meja',
        'no_meja'
    );
    DropdownManager.setupHiddenFieldBinding('tambah_no_meja', 'hidden_tambah_no_meja');
    await DropdownManager.populateDropdown(
        document.getElementById('edit_no_meja'),
        mejaList,
        'no_meja',
        'no_meja'
    );

    await DropdownManager.populateDropdown(
        document.getElementById('tambah_cara'),
        CaraEnum,
        null,
        null
    );
    await DropdownManager.populateDropdown(
        document.getElementById('edit_cara'),
        CaraEnum,
        null,
        null
    );
});