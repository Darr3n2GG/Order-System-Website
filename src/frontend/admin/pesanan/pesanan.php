<?php
require_once dirname(__FILE__, 2) . "/adminBootstrap.php";
require_once dirname(__FILE__, 3) . "/dependencies.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo auto_version("../../style.css"); ?>">
    <link rel="stylesheet" href="<?php echo auto_version("pesanan.css"); ?>">
    <?php
    echoShoelaceStyle();
    echoTabulatorStyle();
    echoAdminHeaderStylesheet();
    echoNavBarStylesheet();
    ?>
    <title>Senarai Pesanan</title>
</head>

<body>
    <?php
    echoNavBar(NAVBAR_PESANAN);
    echoAdminHeader("Senarai Pesanan");
    ?>
    <div class="content container">
        <div class="container_pesanan">
            <div class="table_container">
                <div class="filter">
                    <div class="filter_item">
                        <span>Pelanggan:</span>
                        <sl-input id="filter_id_pelanggan" size="small" placeholder="Cari pelanggan..."></sl-input>
                    </div>
                </div>
                <div class="print_button_container" style="margin-top: 12px;">
                    <sl-button size="small" id="print_button" variant="primary">
                        <sl-icon slot="prefix" name="printer"></sl-icon>
                        Cetak
                    </sl-button>
                </div>
                <div id="table_pesanan"></div>
            </div>
            <div class="toolbar">
                <h2>Tambah Pesanan</h2>
                <form class="form_pesanan" autocomplete="off">
                    <sl-input id="tambah_id_pelanggan" name="id_pelanggan" placeholder="ID Pelanggan">
                        <label slot="label">ID Pelanggan</label>
                        <sl-icon name="person" slot="prefix"></sl-icon>
                    </sl-input>
                    <sl-input id="tambah_tarikh" name="tarikh" type="date">
                        <label slot="label">Tarikh</label>
                        <sl-icon name="calendar" slot="prefix"></sl-icon>
                    </sl-input>
                    <sl-input id="tambah_status" name="status" placeholder="Status (1,2,3,4)">
                        <label slot="label">Status</label>
                        <sl-icon name="info-circle" slot="prefix"></sl-icon>
                    </sl-input>
                    <sl-input id="tambah_cara" name="cara" placeholder="Cara">
                        <label slot="label">Cara</label>
                    </sl-input>
                    <sl-input id="tambah_meja" name="meja" placeholder="Meja">
                        <label slot="label">Meja</label>
                    </sl-input>
                    <div class="form_buttons">
                        <sl-button type="submit">
                            <sl-icon slot="prefix" name="plus-square"></sl-icon>
                            Tambah Pesanan
                        </sl-button>
                        <sl-tooltip content="CSV mesti ada header">
                            <sl-button class="csv_input">Import CSV</sl-button>
                        </sl-tooltip>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <sl-dialog class="edit_dialog" label="Edit Pesanan">
        <div class="form_container">
            <form class="edit_form">
                <input id="edit_id_pesanan" name="id" type="hidden">
                <sl-input id="edit_tarikh" name="tarikh" type="date" label="Tarikh"></sl-input>
                <sl-input id="edit_status" name="id_status" label="Status"></sl-input>
                <sl-input id="edit_cara" name="cara" label="Cara"></sl-input>
                <sl-input id="edit_meja" name="no_meja" label="Meja"></sl-input>
            </form>
        </div>
        <sl-button class="edit_button" slot="footer">Edit</sl-button>
        <sl-button class="cancel_button" slot="footer" variant="danger">Cancel</sl-button>
    </sl-dialog>

    <script type="module" src="<?php echo auto_version("table_pesanan.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("pesanan.js"); ?>"></script>
    <?php
    echoTabulator();
    echoShoelaceAutoloader();
    echoNavBarJavascript();
    ?>
</body>

</html>