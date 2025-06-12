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
                    <sl-select id="tambah_id_pelanggan" placeholder="Pilih Pelanggan">
                        <label slot="label">ID Pelanggan</label>
                        <sl-icon name="person" slot="prefix"></sl-icon>
                        <!-- Options will be added dynamically -->
                    </sl-select>
                    <input type="hidden" name="id_pelanggan" id="hidden_tambah_id_pelanggan">
                    <sl-input id="tambah_tarikh" name="tarikh" type="date">
                        <label slot="label">Tarikh</label>
                        <sl-icon name="calendar" slot="prefix"></sl-icon>
                    </sl-input>
                    <sl-select id="tambah_id_status" placeholder="Pilih Status">
                        <label slot="label">Status</label>
                        <sl-icon name="info-circle" slot="prefix"></sl-icon>
                        <!-- Options will be added dynamically -->
                    </sl-select>
                    <input type="hidden" name="id_status" id="hidden_tambah_id_status">
                    <sl-select placeholder="Pilih Cara" name="cara" id="tambah_cara" placement="bottom">
                        <label slot="label">Cara</label>
                        <!-- Options will be added dynamically -->
                    </sl-select>
                    <sl-select id="tambah_no_meja" placeholder="Pilih Meja">
                        <label slot="label">Meja</label>
                        <!-- Options will be added dynamically -->
                    </sl-select>
                    <input type="hidden" name="no_meja" id="hidden_tambah_no_meja">
                    <div class="form_buttons">
                        <sl-button type="submit">
                            <sl-icon slot="prefix" name="plus-square"></sl-icon>
                            Tambah Pesanan
                        </sl-button>
                        <sl-divider></sl-divider>
                        <sl-tooltip content="CSV mesti ada header">
                            <sl-button class="csv_input">Import CSV</sl-button>
                        </sl-tooltip>
                        <sl-button class="csv_upload">Upload CSV</sl-button>
                    </div>
                </form>
                <ul class="files_list">
                    <p class="include_tag hide">Files included :</p>
                </ul>
            </div>
        </div>
    </div>

    <sl-dialog class="edit_dialog" label="Edit Pesanan">
        <div class="form_container">
            <form class="edit_form">
                <input id="edit_id_pesanan" name="id" type="hidden">
                <input id="edit_id_pelanggan" name="id_pelanggan" type="hidden">
                <sl-input id="edit_tarikh" name="tarikh" type="date" label="Tarikh"></sl-input>
                <sl-select id="edit_id_status" name="id_status" label="Status">
                    <label slot="label">Status</label>
                    <!-- Options will be added dynamically -->
                </sl-select>
                <sl-select id="edit_cara" name="cara" label="Cara">
                    <label slot="label">Cara</label>
                    <!-- Options will be added dynamically -->
                </sl-select>
                <sl-select id="edit_no_meja" name="no_meja" label="Meja">
                    <label slot="label">Meja</label>
                    <!-- Options will be added dynamically -->
                </sl-select>
            </form>
        </div>
        <sl-button class="edit_button" slot="footer">Edit</sl-button>
        <sl-button class="cancel_button" slot="footer" variant="danger">Cancel</sl-button>
    </sl-dialog>

    <div id="receiptModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); width:80%; max-width:600px; background:white; padding:20px; border:1px solid #ccc; box-shadow:0 0 10px rgba(0,0,0,0.3); z-index:9999;">
    <div style="text-align:right;">
        <button id="close_receipt_button">Tutup</button>
    </div>
    <iframe id="receiptFrame" src="" style="width:100%; height:400px; border:none;"></iframe>
    </div>

    <div id="modalBackdrop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9998;"></div>

    <script type="module" src="<?php echo auto_version("table_pesanan.js"); ?>"></script>
    <?php
    echoTabulator();
    echoShoelaceAutoloader();
    echoNavBarJavascript();
    ?>
</body>

</html>