import FetchHelper from "../../../scripts/FetchHelper.js";

const ApiUrl = "/Order-System-Website/src/backend/api/PelangganAPI.php"

let editingRow = null;

const tablePelanggan = new Tabulator("#table_pelanggan", {
    ajaxURL: ApiUrl,
    ajaxConfig: { method: "GET" },
    ajaxRequestFunc: (url, config) => getTableData(url, config),
    height: 510,
    rowHeight: 40,
    layout: "fitData",
    columns: [
        {
            title: "ID",
            field: "id",
        },
        {
            title: "Nama",
            field: "nama",
            width: 177.8 + 87,
            editor: "input",
            editable: (cell) => updateEditable(cell),
            cellEditing: function (cell) {
                editingRow = cell.getRow();
            }
        },
        {
            title: "Nombor Phone",
            field: "no_phone",
            editor: "input",
            editable: (cell) => updateEditable(cell),
            cellEditing: function (cell) {
                editingRow = cell.getRow();
            }
        },
        {
            title: "",
            field: "update",
            hozAlign: "center",
            resizable: false,
            headerSort: false,
            formatter: function () {
                return '<sl-icon-button name="pencil"></sl-icon-button>';
            },
            cellClick: (e, cell) => toggleUpdate(e, cell)
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
    // TODO : fix this aaaa 11:33pm
    rowUpdated: function (row) {
        const data = row.getData();
        console.log("Row was updated:", data);
    }
});

document.addEventListener("mousedown", (event) => {
    if (!editingRow) return;

    const rowElement = editingRow.getElement();
    // TODO : make sure when id column pressed it will still lose focus
    if (!rowElement.contains(event.target)) {
        handleEditLostFocus(editingRow);
        editingRow = null;
    }
});

function handleEditLostFocus(row) {
    const data = row.getData();
    data._editable = false;
    row.update(data, { done: true });
}

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

function toggleUpdate(e, cell) {
    const row = cell.getRow();
    const data = row.getData();
    const isCurrentlyEditable = data._editable;

    resetEditable(cell)

    if (!isCurrentlyEditable) {
        data._editable = !data._editable;
        row.update(data).then(() => {
            const columns = cell.getTable().getColumns();

            for (let col of columns) {
                const field = col.getField();
                const colDef = col.getDefinition();

                if (field && colDef.editable) {
                    const editableCell = row.getCell(field);
                    if (editableCell) {
                        editableCell.edit();
                        break;
                    }
                }
            }
        });
    }
}

function resetEditable(cell) {
    const table = cell.getTable();
    table.getRows().forEach(row => {
        const data = row.getData();
        if (data._editable) {
            data._editable = false;
            row.update(data);
        }
    });
}

function updateEditable(cell) {
    const data = cell.getRow().getData();
    return data._editable;
}

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