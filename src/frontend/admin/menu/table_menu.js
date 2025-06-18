// table_pelanggan.js
import TableManager from "../shared/TableManager.js";
import ViewModel from "./ProdukViewModel.js";
import KategoriViewModel from "./KategoriViewModel.js";
import DropdownManager from "../shared/DropdownManager.js";

// Define Columns of Tabulator table
const columns = [
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

            return `<img class="gambar_produk" src="${value}" onerror="this.onerror=null; this.src='/Order-System-Website/src/assets/produk/placeholder.png';">`;
        }
    },
    {
        title: "",
        field: "update",
        hozAlign: "center",
        headerSort: false,
        formatter: () => '<sl-icon-button name="pencil"></sl-icon-button>',
        cellClick: (e, cell) => {
            const imagePreview = document.getElementById('editImagePreview');
            const imagePreviewContainer = document.getElementById('editImagePreviewContainer');
            imagePreview.src = '';
            imagePreviewContainer.style.display = 'none';
            // show Edit dialog with the data from getData
            // data field : edit field id
            produkTable.showEditDialog(cell.getData(), {
                id: "edit_produk_id",
                nama: "edit_produk_nama",
                id_kategori: "edit_produk_id_kategori",
                harga: "edit_produk_harga",
                maklumat: "edit_produk_maklumat",
                gambar: "edit_produk_gambar"
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
    nama: document.getElementById("filter_nama")
};

// 🔸 Initialize Table Manager
const produkTable = new TableManager({
    tableId: "#table_menu",
    viewModel: ViewModel,
    columns,
    filters
});

// Setup Tambah Form
const formConfig = {
    formElement: document.querySelector(".form_produk"),
    formValidity: {
        // fields to validate in tambah form
        // element id: database field
        tambah_produk_nama: { condition: (v) => ViewModel.validateField("nama", v) },
        tambah_produk_id_kategori: { condition: (v) => ViewModel.validateField("id_kategori", v) },
        tambah_produk_maklumat: { condition: (v) => ViewModel.validateField("maklumat", v) },
        tambah_produk_harga: { condition: (v) => ViewModel.validateField("harga", v) }
    },
    onSubmit: async (formData) => {
        // If a photo was selected earlier
        if (selectedFormData) {
            const uploadResult = await ViewModel.uploadPhoto(selectedFormData);
            if (!uploadResult || !uploadResult.imagePath) {
                alert("Image upload failed.");
                return;
            }

            // Replace the file with the image path string in formData
            formData.set('gambar', uploadResult.imagePath);
            document.getElementById('imagePreviewContainer').style.display = 'none';

            // Optionally reset the selectedFormData
            selectedFormData = null;
        }
    }
};

produkTable.setupFormSubmit(formConfig);

// Setup Edit Form
const dialogConfig = {
    formElement: document.querySelector(".edit_produk_form"),
    dialogElement: document.querySelector(".edit_produk_dialog"),
    formValidity: {
        // fields to validate in edit dialog
        // element id: database field
        edit_produk_nama: { condition: (v) => ViewModel.validateField("nama", v) },
        edit_produk_id_kategori: { condition: (v) => ViewModel.validateField("id_kategori", v) },
        edit_produk_maklumat: { condition: (v) => ViewModel.validateField("maklumat", v) },
        edit_produk_harga: { condition: (v) => ViewModel.validateField("harga", v) }
    }
};
produkTable.setupEditDialog(dialogConfig);

// Setup CSV Import
const csvConfig = {
    csvInput: document.querySelector(".produk_csv_input"),
    filesList: document.querySelector(".produk_files_list"),
    csvUpload: document.querySelector(".csv_upload")
};
produkTable.setupCSVImport(csvConfig);

// Call print method
document.getElementById("print_button").addEventListener("click", () => {
    produkTable.printReport();  // This will print the table, ignoring columns with empty titles
});

// Drop down lists
document.addEventListener('DOMContentLoaded', async () => {
    const kategoriList = await KategoriViewModel.getData(); // already JSON
    await DropdownManager.populateDropdown(
        document.getElementById('tambah_produk_id_kategori'),
        kategoriList,
        'id',
        'nama'
    );
});

document.getElementById('tambah_gambar').addEventListener('click', () => {
    document.getElementById('tambah_produk_gambar').click();
});
let selectedFormData = null;
document.getElementById('tambah_produk_gambar').addEventListener('change', async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    // Preview the image
    const imagePreview = document.getElementById('imagePreview');
    imagePreview.src = URL.createObjectURL(file);
    document.getElementById('imagePreviewContainer').style.display = 'block';

    // Prepare FormData but don't upload yet
    const formData = new FormData();
    formData.append('image', file);
    selectedFormData = formData;
});

document.getElementById('edit_gambar').addEventListener('click', () => {
    document.getElementById('edit_file_produk_gambar').click();
});

document.getElementById('edit_file_produk_gambar').addEventListener('change', async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    // Show the loading preview immediately after selection
    const imagePreview = document.getElementById('editImagePreview');
    imagePreview.src = URL.createObjectURL(file);
    document.getElementById('editImagePreviewContainer').style.display = 'block';

    const formData = new FormData();
    formData.append('image', file);

    const data = await ViewModel.uploadPhoto(formData);
    const imagePath = data.imagePath;
    document.getElementById('edit_produk_gambar').value = imagePath;
    // Update the image preview with the image path from the server
    imagePreview.src = imagePath;
});
