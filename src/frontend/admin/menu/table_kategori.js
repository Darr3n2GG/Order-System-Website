// table_kategori.js
import TableManager from "../shared/TableManager.js";
import ViewModel from "./KategoriViewModel.js";

// Define Columns of Tabulator table
const columns = [
    { title: "ID", field: "id", },
    { title: "Label", field: "label" },
    { title: "Nama", field: "nama", width: "65%" },
    {
        title: "",
        field: "update",
        hozAlign: "center",
        headerSort: false,
        formatter: () => '<sl-icon-button name="pencil"></sl-icon-button>',
        cellClick: (e, cell) => {
            // show Edit dialog with the data from getData
            // data field : edit field id
            kategoriTable.showEditDialog(cell.getData(), {
                id: "edit_kategori_id",
                label: "edit_kategori_label",
                nama: "edit_kategori_nama",
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

// 🔸 Initialize Table Manager
const kategoriTable = new TableManager({
    tableId: "#table_kategori",
    viewModel: ViewModel,
    columns,
    filters: {}
});

// Setup Tambah Form
const formConfig = {
    formElement: document.querySelector(".form_kategori"),
    formValidity: {
        // fields to validate in tambah form
        // element id: database field
        tambah_kategori_label: { condition: (v) => ViewModel.validateField("label", v) },
        tambah_kategori_nama: { condition: (v) => ViewModel.validateField("nama", v) }
    }
};
kategoriTable.setupFormSubmit(formConfig);

// Setup Edit Form
const dialogConfig = {
    formElement: document.querySelector(".edit_kategori_form"),
    dialogElement: document.querySelector(".edit_kategori_dialog"),
    formValidity: {
        // fields to validate in edit dialog
        // element id: database field
        edit_kategori_label: { condition: (v) => ViewModel.validateField("label", v) },
        edit_kategori_nama: { condition: (v) => ViewModel.validateField("nama", v) }
    }
};
kategoriTable.setupEditDialog(dialogConfig);

// Setup CSV Import
const csvConfig = {
    csvInput: document.querySelector(".kategori_csv_input"),
    filesList: document.querySelector(".kategori_files_list"),
    csvUpload: document.querySelector(".csv_upload")
};
kategoriTable.setupCSVImport(csvConfig);

// Call print method
document.getElementById("print_kategori_button").addEventListener("click", () => {
    kategoriTable.printReport();  // This will print the table, ignoring columns with empty titles
});
