new Tabulator("#table_senarai_pesanan", {
    layout: "fitData",
    columns: [
        { title: "No", field: "id", },
        { title: "Status", field: "status" },
        { title: "Tarikh", field: "tarikh", },
        { title: "Cara", field: "cara" },
        { title: "Nombor Meja", field: "no_meja" },
        {
            title: "",
            field: "view",
            hozAlign: "center",
            resizable: false,
            headerSort: false,
            formatter: function () {
                return '<sl-icon-button name="eye"></sl-icon-button>';
            },
            cellClick: (e, cell) => showViewDialog(e, cell)
        }
    ],
})