// table_pelanggan.js
import TableManager from "../shared/TableManager.js";
import ViewModel from "./PelangganViewModel.js";
// import { FileInput } from "../../../scripts/FileInput.js"

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI.php";

const TahapEnum = {
    "1": "User",
    "2": "Admin"
};

// let files_received = null;
// globalThis.logFiles = () => {
//     return files_received;
// };

// const CSVInput = document.querySelector(".csv_input");
// const filesList = document.querySelector(".files_list");
// const CSVUpload = document.querySelector(".csv_upload");

// const fileInput = new FileInput(true, ".csv");

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

// 🔸 Dialog Config
const dialogConfig = {
    editDialog: document.querySelector(".edit_dialog"),
    editForm: document.querySelector(".edit_form"),
    buttonSelector: ".edit_button",
    formValidity: {
        edit_id: { condition: () => "" },
        edit_nama: { condition: (v) => ViewModel.validateField("nama", v) },
        edit_nombor_phone: { condition: (v) => ViewModel.validateField("no_phone", v) },
        edit_tahap: { condition: (v) => ViewModel.validateField("tahap", v) }
    },
    onSubmit: (formData) => ViewModel.updateData(formData)
};

// 🔸 Form Config
const formConfig = {
    formElement: document.querySelector(".form_pelanggan"),
    formValidity: {
        tambah_nama: { condition: (v) => ViewModel.validateField("nama", v) },
        tambah_no_phone: { condition: (v) => ViewModel.validateField("no_phone", v) },
        tambah_password: { condition: (v) => ViewModel.validateField("password", v) },
        tambah_tahap: { condition: (v) => ViewModel.validateField("tahap", v) }
    },
    onSubmit: (formData) => ViewModel.insertData(formData)
};

// 🔸 Initialize Table Manager
const pelangganTable = new TableManager({
    tableId: "#table_pelanggan",
    apiUrl: ApiUrl,
    viewModel: ViewModel,
    columns,
    filters,
    dialogConfig,
    formConfig
});

pelangganTable.setupCSVImport({
    inputSelector: ".csv_input",
    listSelector: ".files_list",
    uploadSelector: ".csv_upload"
});

// 🔸 Sync Hidden Dropdown
document.getElementById("hidden_tambah_tahap").value = document.getElementById("tambah_tahap").value;

document.getElementById("tambah_tahap").addEventListener("sl-change", (event) => {
    document.getElementById("hidden_tambah_tahap").value = event.target.value;
});

document.getElementById("print_button").addEventListener("click", async () => {
    const data = await ViewModel.getData();

    const printWindow = window.open('', '', 'width=800,height=600');
    if (!printWindow) return;

    const tableHTML = `
        <html>
        <head>
            <title>Cetak Pelanggan</title>
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #333; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                body { font-family: sans-serif; padding: 20px; }
            </style>
        </head>
        <body>
            <h2>Senarai Pelanggan</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>No. Phone</th>
                        <th>Tahap</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.map(row => `
                        <tr>
                            <td>${row.id}</td>
                            <td>${row.nama}</td>
                            <td>${row.no_phone}</td>
                            <td>${TahapEnum[row.tahap] || row.tahap}</td>
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

// Import CSV
// CSVInput.addEventListener("click", () => {
//     fileInput.clickInput();
//     filesList.innerHTML = "<p class='include_tag hide'>Files included :</p>";
//     files_received = null;
// });

// fileInput.getInput().addEventListener("change", ({ target }) => {
//     files_received = target.files;

//     const includeTag = filesList.querySelector(".include_tag");
//     if (files_received.length === 0) {
//         includeTag.classList.add("hide");
//     } else {
//         includeTag.classList.remove("hide");
//         for (const file of files_received) {
//             filesList.insertAdjacentHTML("beforeend",
//                 `<li>${file.name}</li>`
//             );
//         }
//     }
// });

// CSVUpload.addEventListener("click", async () => {
//     if (files_received != null) {
//         const data = new FormData;
//         for (const file of files_received) {
//             data.append("files[]", file)
//         }
//         const result = await ViewModel.postCSV(data);
//         if (result.ok) {
//             alert("File CSV dihantar.");
//         } else {
//             alert("File CSV gagal dihantar.");
//         }
//     }
// });