// TableManager.js
import FormValidator from "../../../scripts/FormValidator.js";
import { FileInput } from "../../../scripts/FileInput.js"

class TableManager {
    constructor({ tableId, apiUrl, viewModel, columns, filters = {}, dialogConfig = {}, formConfig = {} }) {
        this.apiUrl = apiUrl;
        this.viewModel = viewModel;

        this.table = new Tabulator(tableId, {
            ajaxURL: apiUrl,
            height: 510,
            rowHeight: 40,
            layout: "fitData",
            ajaxRequestFunc: () => viewModel.getData(),
            columns
        });

        this.filters = filters;
        this.bindFilters();

        if (dialogConfig.editDialog) {
            this.editDialog = dialogConfig.editDialog;
            this.editForm = dialogConfig.editForm;
            this.editFormValidity = dialogConfig.formValidity;
            this.bindEditDialog(dialogConfig);
        }

        if (formConfig.formElement) {
            this.form = formConfig.formElement;
            this.formValidity = formConfig.formValidity;
            this.bindFormSubmit(formConfig);
        }
    }

    bindFilters() {
        Object.entries(this.filters).forEach(([field, inputElement]) => {
            inputElement.addEventListener("sl-change", (e) => {
                this.viewModel.filters[field] = e.target.value.trim().toLowerCase();
                this.table.setData(); // Trigger reload
            });
        });
    }

    bindFormSubmit({ onSubmit }) {
        this.form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const isValid = FormValidator.validateForm(this.formValidity);
            if (!isValid) return;

            const formData = new FormData(this.form);
            const result = await onSubmit(formData);

            if (result.ok) {
                this.table.setData(this.apiUrl);
                this.form.reset();
                alert("Operation successful.");
            } else {
                alert("Operation failed. Please try again.");
                console.error("Operation failed:", result);
            }
        });

        this.form.addEventListener("input", (event) => {
            FormValidator.validateField(this.formValidity, event.target.id);
        });
    }

    bindEditDialog({ buttonSelector, onSubmit }) {
        const button = this.editDialog.querySelector(buttonSelector);
        button.addEventListener("click", async () => {
            if (!FormValidator.validateForm(this.editFormValidity)) return;

            const formData = new FormData(this.editForm);
            const result = await onSubmit(formData);

            if (result.ok) {
                alert("Berjaya kemaskini.");
                this.table.setData();
                this.editDialog.hide();
            }
        });

        this.editForm.addEventListener("input", (event) => {
            FormValidator.validateField(this.editFormValidity, event.target.id);
        });
    }

    showEditDialog(data, fields = {}) {
        Object.entries(fields).forEach(([key, selector]) => {
            const el = document.getElementById(selector);
            if (el) el.value = String(data[key]);
        });

        if (this.editDialog) this.editDialog.show();
    }

    setupCSVImport({ inputSelector, listSelector, uploadSelector }) {
        const fileInput = new FileInput(true, ".csv");
        const CSVInput = document.querySelector(inputSelector);
        const filesList = document.querySelector(listSelector);
        const CSVUpload = document.querySelector(uploadSelector);
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
                const result = await this.viewModel.postCSV(data);
                if (result.ok) {
                    alert("File CSV dihantar.");
                    this.table.setData(); // Refresh table
                } else {
                    alert("File CSV gagal dihantar.");
                }
            }
        });
    }
    
}

export default TableManager;