// table_pelanggan.js
import ViewModel from "./PelangganViewModel.js";
import FormValidator from "../../../scripts/FormValidator.js";
const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI.php"

const editDialog = document.querySelector(".edit_dialog");
const editForm = editDialog.querySelector(".edit_form");
const formPelanggan = document.querySelector(".form_pelanggan");

const tablePelanggan = new Tabulator("#table_pelanggan", {
    ajaxURL: ApiUrl,
    height: 510,
    rowHeight: 40,
    layout: "fitData",
    ajaxRequestFunc: () => ViewModel.getData(),
    columns: [
        { title: "ID", field: "id" },
        { title: "Nama", field: "nama", width: 250 },
        { title: "Nombor Phone", field: "no_phone" },
        { title: "Tahap", field: "tahap" ,
            formatter: (cell) => {
                // Convert 1 to "User" and 2 to "Admin"
                const tahapEnum = {
                    "1": "User",
                    "2": "Admin"
                };
                return tahapEnum[cell.getValue()] || cell.getValue(); 
            }
        },
        {
            title: "", field: "update", hozAlign: "center", headerSort: false,
            formatter: () => '<sl-icon-button name="pencil"></sl-icon-button>',
            cellClick: (e, cell) => showEditDialog(cell.getData())
        },
        {
            title: "", field: "delete", hozAlign: "center", headerSort: false,
            formatter: () => '<sl-icon-button name="trash"></sl-icon-button>',
            cellClick: async (e, cell) => {
                const id = cell.getData().id;
                const result = await ViewModel.deleteData(id);
            
                if (result.ok) {
                    cell.getRow().delete();
                    alert("Pelanggan dipadam.");
                } else {
                    alert("Gagal memadam pelanggan. Sila cuba lagi.");
                    console.error("Delete failed:", result);
                }
            }
            
        }
    ]
});

// Add New
const tambahFormValidity = {
    tambah_nama: { condition: (value) => ViewModel.validateNama(value) },
    tambah_no_phone: { condition: (value) => ViewModel.validatePhone(value) },
    tambah_password: { condition: (value) => ViewModel.validatePassword(value) },
    tambah_tahap: { condition: (value) => ViewModel.validateTahap(value) }
};


formPelanggan.addEventListener("submit", async (event) => {
    event.preventDefault();
    const nama = document.getElementById("tambah_nama").value;
    const no_phone = document.getElementById("tambah_no_phone").value;
    const password = document.getElementById("tambah_password").value;
    const tahap = document.getElementById("tambah_tahap").value;
    
    // Check for empty fields
    if (!nama || !no_phone || !password || !tahap) {
        alert("Sila isi semua ruangan wajib.");
        return; // Stop submission
    }
    const formData = new FormData(formPelanggan);
    try {
        // Call insertData and await the result
        const result = await ViewModel.insertData(formData);
        
        if (result.ok) {
            // If insertion is successful, update the table
            tablePelanggan.setData(ApiUrl);
            alert("Pelanggan added successfully.");

            // Clear the form after successful submission
            formPelanggan.reset();
        } else {
            // If there's an error (non-2xx status)
            alert("Failed to add Pelanggan. Please try again.");
            console.error("Insert failed:", result);
        }
    } catch (error) {
        // Handle any unexpected errors
        alert("An error occurred. Please try again.");
        console.error("Error during insert:", error);
    }
});

formPelanggan.addEventListener("input", (event) => {
    const id = event.target.id;
    FormValidator.validateField(tambahFormValidity, id);
});

const tahapSelect = document.getElementById("tambah_tahap");
const hiddenTahapInput = document.getElementById("hidden_tambah_tahap");

tahapSelect.addEventListener("sl-change", (event) => {
    hiddenTahapInput.value = event.target.value;
});

// Optional: sync on page load if needed
hiddenTahapInput.value = tahapSelect.value;

// Filter
document.getElementById("filter_nama").addEventListener("sl-change", (e) => {
    console.log("FILTER NAMA");
    ViewModel.filterNama = e.target.value.trim().toLowerCase();
    tablePelanggan.setData("abc");
});

document.getElementById("filter_no_phone").addEventListener("sl-change", (e) => {
    ViewModel.filterPhone = e.target.value.trim().toLowerCase();
    tablePelanggan.setData();
});

// Edit
function showEditDialog(data) {
    document.getElementById("edit_id").value = data.id;
    document.getElementById("edit_nama").value = data.nama;
    document.getElementById("edit_nombor_phone").value = data.no_phone;
    
    // Set the value of the sl-select dropdown and trigger the change event
    const tahapSelect = document.getElementById("edit_tahap");
    tahapSelect.value = String(data.tahap);
    editDialog.show();
}

editDialog.querySelector(".edit_button").addEventListener("click", async () => {
    if (FormValidator.validateForm(editFormValidity)) {
        const formData = new FormData(editForm);
        console.log(formData)
        const result = await ViewModel.updateData(formData);

        if (result.ok) {
            alert("Berjaya kemaskini.");
            tablePelanggan.setData();
            editDialog.hide();
        }
    }
});

const editFormValidity = {
    edit_id: { condition: () => "" },
    edit_nama: { condition: ViewModel.validateNama },
    edit_nombor_phone: { condition: ViewModel.validatePhone },
    edit_tahap: { condition: ViewModel.validateTahap }
};



editForm.addEventListener("input", (event) => {
    const id = event.target.id;
    FormValidator.validateField(editFormValidity, id);
});


