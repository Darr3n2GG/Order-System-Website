<?php
require_once dirname(__FILE__, 2) . "/adminBootstrap.php";
require_once dirname(__FILE__, 3) . "/dependencies.php";
require_once dirname(__FILE__, 4) . "/backend/Database.php";

$Database = createDatabaseConn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo auto_version("../../style.css"); ?>">
    <link rel="stylesheet" href="<?php echo auto_version("senarai_menu.css"); ?>">
    <?php
    echoShoelaceStyle();
    echoTabulatorStyle();
    echoAdminHeaderStylesheet();
    echoNavBarStylesheet();
    ?>
    <title>Senarai Produk</title>
</head>

<body>
    <?php
    echoNavBar(NAVBAR_MENU);
    echoAdminHeader("Menu");
    ?>
    <div class="content container">
        <div class="menu_tab_group">
            <sl-tab-group>
                <sl-tab slot="nav" panel="produk">Produk</sl-tab>
                <sl-tab slot="nav" panel="kategori">Kategori</sl-tab>

                <!-- Produk Panel -->
                <sl-tab-panel name="produk">
                    <div class="container_menu">
                        <div class="table_container">
                            <div class="filter">
                                <div class="filter_item">
                                    <span>Nama:</span>
                                    <sl-input id="filter_nama" size="small" placeholder="Cari Nama..."></sl-input>
                                </div>
                            </div>
                            <div>
                                <sl-button size="small" variant="primary" id="print_button" style="margin-left: auto;">
                                    <sl-icon slot="prefix" name="printer"></sl-icon>
                                    Cetak
                                </sl-button>
                            </div>
                            <div id="table_menu"></div>
                        </div>
                        <div class="toolbar">
                            <h2>Tambah Produk</h2>
                            <form class="form_produk" autocomplete="off">
                                <sl-input placeholder="Nama" id="tambah_produk_nama" name="nama" type="text" autocomplete="off" required>
                                    <label slot="label">Nama</label>
                                    <sl-icon name="card-text" slot="prefix"></sl-icon>
                                </sl-input>
                                <sl-select placeholder="Kategori" id="tambah_produk_id_kategori" placement="bottom" required>
                                    <sl-icon name="tag" slot="prefix"></sl-icon>
                                    <label slot="label">Kategori</label>
                                </sl-select>
                                <input type="hidden" name="id_kategori" id="hidden_tambah_produk_id_kategori">
                                <sl-input placeholder="Harga" id="tambah_produk_harga" name="harga" type="number" autocomplete="off" required>
                                    <label slot="label">Harga</label>
                                    <sl-icon name="cash" slot="prefix"></sl-icon>
                                </sl-input>
                                <input type="file" id="tambah_produk_gambar" accept="image/*" style="display: none;">
                                <sl-textarea id="tambah_produk_maklumat" name="maklumat" label="Maklumat" placeholder="Tulis maklumat produk di situ." resize="auto" required></sl-textarea>
                                <div id="imagePreviewContainer" style="display:none;">
                                    <label for="imagePreview" id="imagePreviewLabel">Preview Gambar</label>
                                    <img id="imagePreview" src="" alt="Image Preview" style="max-width: 100px; max-height: 100px;">
                                </div>
                                <div class="form_buttons">
                                    <sl-button id="tambah_gambar">
                                        <sl-icon slot="prefix" name="card-image"></sl-icon>
                                        Tambah Gambar
                                    </sl-button>
                                    <sl-button type="submit">
                                        <sl-icon slot="prefix" name="plus-square"></sl-icon>
                                        Tambah Produk
                                    </sl-button>
                                    <sl-divider></sl-divider>
                                    <sl-tooltip content="CSV mesti ada header dan gambar">
                                        <sl-button id="produk_csv_input" class="csv_input">Import CSV</sl-button>
                                    </sl-tooltip>
                                    <sl-button class="csv_upload">Upload CSV</sl-button>
                                </div>
                            </form>
                            <ul id="produk_files_list" class="produk_files_list">
                                <p class="include_tag hide">Files included :</p>
                            </ul>
                        </div>
                    </div>
                </sl-tab-panel>

                <!-- Kategori Panel -->
                <sl-tab-panel name="kategori">
                    <div class="container_menu">
                        <div class="table_container">
                            <div class="print_button_container" style="margin-top: 12px;">
                                <sl-button size="small" id="print_kategori_button" variant="primary">
                                    <sl-icon slot="prefix" name="printer"></sl-icon>
                                    Cetak
                                </sl-button>
                            </div>
                            <div id="table_kategori"></div>
                        </div>
                        <div class="toolbar">
                            <h2>Tambah Kategori</h2>
                            <form class="form_kategori" autocomplete="off">
                                <sl-input placeholder="Label" id="tambah_kategori_label" name="label" type="text" autocomplete="off" maxlength="1" required>
                                    <label slot="label">Label</label>
                                    <sl-icon name="bookmark" slot="prefix"></sl-icon>
                                </sl-input>
                                <sl-input placeholder="Nama" id="tambah_kategori_nama" name="nama" type="text" autocomplete="off" required>>
                                    <label slot="label">Nama</label>
                                    <sl-icon name="card-text" slot="prefix"></sl-icon>
                                </sl-input>
                                <div class="form_buttons">
                                    <sl-button type="submit">
                                        <sl-icon slot="prefix" name="plus-square"></sl-icon>
                                        Tambah Kategori
                                    </sl-button>
                                    <sl-divider></sl-divider>
                                    <sl-tooltip content="CSV mesti ada header dan gambar">
                                        <sl-button id="kategori_csv_input" class="kategori_csv_input">Import CSV</sl-button>
                                    </sl-tooltip>
                                    <sl-button class="csv_upload">Upload CSV</sl-button>
                                </div>
                            </form>
                            <ul id="kategori_files_list" class="kategori_files_list">
                                <p class="include_tag hide">Files included :</p>
                            </ul>
                        </div>
                    </div>
                </sl-tab-panel>
            </sl-tab-group>
        </div>
    </div>

    <sl-dialog class="edit_produk_dialog" label="Edit Produk">
        <div class="form_container">
            <form class="edit_produk_form">
                <input id="edit_produk_id" name="id" hidden>
                <sl-input placeholder="Nama" id="edit_produk_nama" name="nama" type="text" autocomplete="off">
                    <label slot="label">Nama</label>
                    <sl-icon name="card-text" slot="prefix"></sl-icon>
                </sl-input>
                <sl-select placeholder="Kategori" id="edit_produk_id_kategori" name="id_kategori" placement="bottom">
                    <sl-icon name="tag" slot="prefix"></sl-icon>
                    <label slot="label">Kategori</label>
                </sl-select>
                <sl-input placeholder="Harga" id="edit_produk_harga" name="harga" type="number" autocomplete="off">
                    <label slot="label">Harga</label>
                    <sl-icon name="cash" slot="prefix"></sl-icon>
                </sl-input>
                <sl-textarea id="edit_produk_maklumat" name="maklumat" label="Maklumat" resize="auto"></sl-textarea>
                <div id="editImagePreviewContainer" style="display:none;">
                    <label for="imagePreview" id="imagePreviewLabel">Preview Gambar</label>
                    <img id="editImagePreview" src="" alt="Image Preview" style="max-width: 100px; max-height: 100px;">
                </div>
                <input id="edit_produk_gambar" name="gambar" hidden>
                <input type="file" id="edit_file_produk_gambar" accept="image/*" style="display: none;">
                <sl-button id="edit_gambar">
                    <sl-icon slot="prefix" name="card-image"></sl-icon>
                    Tukar Gambar
                </sl-button>
            </form>
        </div>
        <sl-button id="edit_produk_button" class="edit_button" slot="footer">Edit</sl-button>
        <sl-button id="cancel_edit_produk_button" class="cancel_button" slot="footer" variant="danger">Cancel</sl-button>
    </sl-dialog>

    <sl-dialog class="edit_kategori_dialog" label="Edit Kategori">
        <div class="form_container">
            <form class="edit_kategori_form">
                <input id="edit_kategori_id" name="id" hidden>
                <sl-input placeholder="Label" id="edit_kategori_label" name="label" type="text" autocomplete="off" maxlength="1">
                    <label slot="label">Label</label>
                    <sl-icon name="bookmark" slot="prefix"></sl-icon>
                </sl-input>
                <sl-input placeholder="Nama" id="edit_kategori_nama" name="nama" type="text" autocomplete="off">
                    <label slot="label">Nama</label>
                    <sl-icon name="card-text" slot="prefix"></sl-icon>
                </sl-input>
            </form>
        </div>
        <sl-button id="edit_button" class="edit_button" slot="footer">Edit</sl-button>
        <sl-button id="cancel_button" class="cancel_button" slot="footer" variant="danger">Cancel</sl-button>
    </sl-dialog>

    <script type="module" src="<?php echo auto_version("table_menu.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("table_kategori.js"); ?>"></script>
    <?php
    echoTabulator();
    echoShoelaceAutoloader();
    echoNavBarJavascript();
    echoNoScript();
    ?>
</body>

</html>