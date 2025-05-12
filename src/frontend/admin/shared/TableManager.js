// TableManager.js
import FormValidator from "../../../scripts/FormValidator.js";
import { FileInput } from "../../../scripts/FileInput.js"

class TableManager {
    constructor({ tableId, viewModel, columns, filters = {}}, displayName = "Data") {
        this.viewModel = viewModel;
        this.columns = columns;

        this.table = new Tabulator(tableId, {
            ajaxURL: viewModel.getApiUrl(),
            height: 510,
            rowHeight: 40,
            layout: "fitData",
            ajaxRequestFunc: () => viewModel.getData(),
            columns
        });

        this.filters = filters;
        this.bindFilters();
    }

    bindFilters() {
        Object.entries(this.filters).forEach(([field, inputElement]) => {
            inputElement.addEventListener("sl-change", (e) => {
                this.viewModel.filters[field] = e.target.value.trim().toLowerCase();
                this.table.setData(); // Trigger reload
            });
        });
    }

    setupEditDialog(dialogConfig) {
        const editForm = dialogConfig.formElement || document.querySelector(".edit_form");
        const editDialog = dialogConfig.dialogElement || document.querySelector(".edit_dialog");
        this.editDialog = editDialog;
        const editButton = editDialog.querySelector(".edit_button");
        const cancelButton = editDialog.querySelector(".cancel_button");

        editButton.addEventListener("click", async () => {
            if (!FormValidator.validateForm(dialogConfig.formValidity)) return;

            const formData = new FormData(editForm);
            const result = await this.viewModel.updateData(formData);

            if (result.ok) {
                alert(`Data ${this.viewModel.resourceName} berjaya dikemaskini.`);
                this.table.setData();
                editDialog.hide();
            }
        });

        cancelButton.addEventListener("click", () => {
            editDialog.hide()
        })


        editForm.addEventListener("input", (event) => {
            const fieldId = event.target.id;
            if (dialogConfig.formValidity.hasOwnProperty(fieldId)) {
                FormValidator.validateField(dialogConfig.formValidity, fieldId);
            }
        });
    }

    showEditDialog(data, fields = {}) {
        const editDialog = this.editDialog;
        Object.entries(fields).forEach(([key, selector]) => {
            const el = document.getElementById(selector);
            if (el) el.value = String(data[key]);
        });

        if (editDialog) editDialog.show();
    }

    setupFormSubmit(formConfig) {
        const form = formConfig.formElement;
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const isValid = FormValidator.validateForm(formConfig.formValidity);
            if (!isValid) return;

            const formData = new FormData(form);
            const result = await this.viewModel.insertData(formData);

            if (result.ok) {
                this.table.setData();
                form.reset();
                alert(`${this.viewModel.resourceName} ditambah.`);
            } else {
                alert(`${this.viewModel.resourceName} gagal ditambah.`);
                console.error("Operation failed:", result);
            }
        });

        form.addEventListener("input", (event) => {
            const fieldId = event.target.id;
            if (formConfig.formValidity.hasOwnProperty(fieldId)) {
                FormValidator.validateField(formConfig.formValidity, fieldId);
            }
        });
    }

    setupCSVImport(csvConfig = {}) {
        const fileInput = new FileInput(true, ".csv");
        const CSVInput = csvConfig.csvInput || document.querySelector(".csv_input");
        const filesList = csvConfig.filesList || document.querySelector(".files_list");
        const CSVUpload = csvConfig.csvUpload || document.querySelector(".csv_upload");
        let files_received = null;
    
        globalThis.logFiles = () => files_received;
    
        CSVInput.addEventListener("click", () => {
            fileInput.clickInput();
            filesList.innerHTML = "<p class='include_tag hide'>Files included :</p>";
            files_received = null;
        });
    
        fileInput.getInput().addEventListener("change", ({ target }) => {
            files_received = target.files;
    
            const includeTag = filesList.querySelector(".include_tag");
            if (files_received.length === 0) {
                includeTag.classList.add("hide");
            } else {
                includeTag.classList.remove("hide");
                for (const file of files_received) {
                    filesList.insertAdjacentHTML("beforeend", `<li>${file.name}</li>`);
                }
            }
        });
    
        CSVUpload.addEventListener("click", async () => {
            if (files_received != null) {
                const data = new FormData();
                for (const file of files_received) {
                    data.append("files[]", file);
                }
                const result = await this.viewModel.insertData(data);
                if (result.ok) {
                    alert("File CSV dihantar.");
                    this.table.setData(); // Refresh table
                } else {
                    alert("File CSV gagal dihantar.");
                }
            }
        });
    }

    printReport() {
        // Filter out columns with empty titles only for the print report
        const printColumns = this.columns.filter(col => col.title.trim() !== "");

        const data = this.table.getData(); // Get data from the table

        const printWindow = window.open('', '', 'width=800,height=600');
        if (!printWindow) return;

        const tableHTML = `
            <html>
            <head>
                <title>Cetak Data</title>
                <style>
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid #333; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    body { font-family: sans-serif; padding: 20px; }
                </style>
            </head>
            <body>
                <h2>Senarai ${this.viewModel.resourceName}</h2>
                <table>
                    <thead>
                        <tr>
                            ${printColumns.map(col => `<th>${col.title}</th>`).join("")}
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(row => `
                            <tr>
                                ${printColumns.map(col => `
                                    <td>${this.formatCellValue(row, col)}</td>
                                `).join("")}
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
        printWindow.addEventListener('afterprint', () => {
            printWindow.close();
        });
        printWindow.print();
    }

    formatCellValue(row, col) {
        // If the column has a custom formatter, use it
        if (col.formatter) {
            return col.formatter({ getValue: () => row[col.field] });
        }
        // Default case: just return the raw value
        return row[col.field] || '';
    }
    
}

export default TableManager;