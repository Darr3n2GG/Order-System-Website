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
    <link rel="stylesheet" href="<?php echo auto_version("pelanggan.css"); ?>">
    <?php
    echoShoelaceStyle();
    echoTabulatorStyle();
    echoAdminHeaderStylesheet();
    echoNavBarStylesheet();
    ?>
    <title>Senarai Pelanggan</title>
</head>

<body>
    <?php
    echoNavBar(NAVBAR_PELANGGAN);
    echoAdminHeader("Senarai Pelanggan");
    ?>
    <div class="content container">
        <div class="container_pelanggan">
            <div class="table_container">
                <div class="filter">
                    <div class="filter_item">
                        <span>Nama:</span>
                        <sl-input id="filter_nama" size="small" placeholder="Cari nama..."></sl-input>
                    </div>
                    <div class="filter_item">
                        <span>No.Phone:</span>
                        <sl-input id="filter_no_phone" size="small" placeholder="Cari phone..."></sl-input>
                    </div>
                </div>
                <div class="print_button_container" style="margin-top: 12px;">
                    <sl-button size="small" id="print_button" variant="primary">
                        <sl-icon slot="prefix" name="printer"></sl-icon>
                        Cetak
                    </sl-button>
                </div>
                <div id="table_pelanggan"></div>
            </div>
            <div class="toolbar">
                <h2>Tambah Pelanggan</h2>
                <form class="form_pelanggan" autocomplete="off">
                    <sl-input placeholder="Nama" id="tambah_nama" name="nama" type="text" autocomplete="off">
                        <label slot="label">Nama</label>
                        <sl-icon name="person-circle" slot="prefix"></sl-icon>
                    </sl-input>
                    <sl-input placeholder="contoh: 0123456789" id="tambah_no_phone" name="no_phone" type="tel" autocomplete="off">
                        <label slot="label">Nombor Phone</label>
                        <sl-icon name="telephone" slot="prefix"></sl-icon>
                    </sl-input>
                    <sl-input placeholder="Password" id="tambah_password" name="password" type="password" autocomplete="off" password-toggle>
                        <label slot="label">Password</label>
                        <sl-icon name="key" slot="prefix"></sl-icon>
                    </sl-input>
                    <sl-select placeholder="Tahap" id="tambah_tahap" placement="bottom">
                        <label slot="label">Tahap</label>
                        <sl-option value="1">User</sl-option>
                        <sl-option value="2">Admin</sl-option>
                    </sl-select>
                    <input type="hidden" name="tahap" id="hidden_tambah_tahap">
                    <div class="form_buttons">
                        <sl-button type="submit">
                            <sl-icon slot="prefix" name="plus-square"></sl-icon>
                            Tambah pelanggan
                        </sl-button>
                        <sl-divider></sl-divider>
                        <sl-tooltip content="CSV mesti ada header">
                            <sl-button class="csv_input">Import CSV</sl-button>
                        </sl-tooltip>
                        <sl-button class="csv_upload">Upload CSV</sl-button>
                    </div>
                </form>
                <ul class="files_list">
                    <p class="include_tag hide">Fail-fail yang dimasuk :</p>
                </ul>
            </div>
        </div>
    </div>

    <sl-dialog class="edit_dialog" label="Edit Pelanggan">
        <div class="form_container">
            <form class="edit_form">
                <input id="edit_id" name="id" type="text" hidden>
                <sl-input id="edit_nama" name="nama" label="Nama" placeholder="Masukkan Nama" required></sl-input>
                <sl-input id="edit_nombor_phone" name="no_phone" label="Nombor Phone" placeholder="Masukkan Nombor Phone" required></sl-input>
                <sl-select id="edit_tahap" name="tahap" value="2" placement="bottom">
                    <sl-option value="1">User</sl-option>
                    <sl-option value="2">Admin</sl-option>
                </sl-select>
            </form>
        </div>
        <sl-button class="edit_button" slot="footer">Edit</sl-button>
        <sl-button class="cancel_button" slot="footer" variant="danger">Cancel</sl-button>
    </sl-dialog>

    <script type="module" src="<?php echo auto_version("table_pelanggan.js"); ?>"></script>
    <?php
    echoTabulator();
    echoShoelaceAutoloader();
    echoNavBarJavascript();
    ?>
</body>

</html>