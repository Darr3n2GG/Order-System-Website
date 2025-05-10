// table_pelanggan.js
import TableManager from "../shared/TableManager.js";
import ViewModel from "./PesananViewModel.js";
import PelangganViewModel from "../Pelanggan/PelangganViewModel.js";

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
                alert("Pesanan dipadam.");
            } else {
                alert("Gagal memadam pesanan.");
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

document.addEventListener('DOMContentLoaded', async () => {
    const select = document.getElementById('tambah_id_pelanggan');
  
    try {
      const pelangganList = await PelangganViewModel.getData(); // already JSON
  
      pelangganList.forEach(p => {
        const option = document.createElement('sl-option');
        option.value = p.id;
        option.textContent = `${p.nama}`;
        select.appendChild(option);
      });
    } catch (error) {
      console.error('Failed to load pelanggan:', error);
    }

    const selectStatus = document.getElementById('tambah_id_status');
    const editSelectStatus = document.getElementById('edit_id_status');

    try {
    const statusList = await ViewModel.getStatus(); // already JSON

    statusList.forEach(p => {
        const option1 = document.createElement('sl-option');
        option1.value = p.id;
        option1.textContent = `${p.status}`;
        selectStatus.appendChild(option1);

        const option2 = document.createElement('sl-option');
        option2.value = p.id;
        option2.textContent = `${p.status}`;
        editSelectStatus.appendChild(option2);
    });
    } catch (error) {
    console.error('Failed to load status:', error);
    }

    const selectMeja = document.getElementById('tambah_no_meja');
    const editSelectMeja = document.getElementById('edit_no_meja');

    try {
    const mejaList = await ViewModel.getMeja(); // already JSON
    mejaList.forEach(p => {
        const option1 = document.createElement('sl-option');
        option1.value = p.no_meja;
        option1.textContent = `${p.no_meja}`;
        selectMeja.appendChild(option1);

        const option2 = document.createElement('sl-option');
        option2.value = p.no_meja;
        option2.textContent = `${p.no_meja}`;
        editSelectMeja.appendChild(option2);
    });
    } catch (error) {
    console.error('Failed to load meja:', error);
    }


    const selectCara = document.getElementById('tambah_cara');
    const editSelectCara = document.getElementById('edit_cara');

    try {
    Object.entries(CaraEnum).forEach(([key, value]) => {
        const option1 = document.createElement('sl-option');
        option1.value = value;
        option1.textContent = value;
        selectCara.appendChild(option1);

        const option2 = document.createElement('sl-option');
        option2.value = value;
        option2.textContent = value;
        editSelectCara.appendChild(option2);
    });
    } catch (error) {
    console.error('Failed to load cara options:', error);
    }
  });
  

// set the hidden id field when drop down list changed
// hidden id field is sent when saving to database
document.getElementById("hidden_tambah_id_pelanggan").value = document.getElementById("tambah_id_pelanggan").value;
document.getElementById("tambah_id_pelanggan").addEventListener("sl-change", (event) => {
    document.getElementById("hidden_tambah_id_pelanggan").value = event.target.value;
});

document.getElementById("hidden_tambah_id_status").value = document.getElementById("tambah_id_status").value;
document.getElementById("tambah_id_status").addEventListener("sl-change", (event) => {
    document.getElementById("hidden_tambah_id_status").value = event.target.value;
});

document.getElementById("hidden_tambah_no_meja").value = document.getElementById("tambah_no_meja").value;
document.getElementById("tambah_no_meja").addEventListener("sl-change", (event) => {
    document.getElementById("hidden_tambah_no_meja").value = event.target.value;
});