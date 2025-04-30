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
                                    <sl-input size="small"></sl-input>
                                </div>
                            </div>
                            <div id="table_menu"></div>
                        </div>
                        <div class="toolbar">
                            <h2>Tambah Produk</h2>
                            <form class="form_produk" autocomplete="off">
                                <sl-input placeholder="Nama" id="tambah_produk_nama" name="nama" type="text" autocomplete="off">
                                    <label slot="label">Nama</label>
                                    <sl-icon name="card-text" slot="prefix"></sl-icon>
                                </sl-input>
                                <sl-select placeholder="Kategori" id="tambah_produk_kategori" placement="bottom">
                                    <sl-icon name="tag" slot="prefix"></sl-icon>
                                    <label slot="label">Kategori</label>
                                    <?php loadKategoriOptionHTML(); ?>
                                </sl-select>
                                <sl-input placeholder="Harga" id="tambah_produk_harga" name="harga" type="number" autocomplete="off">
                                    <label slot="label">Harga</label>
                                    <sl-icon name="cash" slot="prefix"></sl-icon>
                                </sl-input>
                                <sl-textarea id="tambah_produk_detail" label="Detail" resize="auto"></sl-textarea>
                                <div class="form_buttons">
                                    <sl-button id="tambah_gambar">
                                        <sl-icon slot="prefix" name="card-image"></sl-icon>
                                        Tambah Gambar
                                    </sl-button>
                                    <sl-button type="submit">
                                        <sl-icon slot="prefix" name="plus-square"></sl-icon>
                                        Tambah Produk
                                    </sl-button>
                                    <sl-tooltip content="CSV mesti ada header dan gambar">
                                        <sl-button id="produk_csv_input" class="csv_input">Import CSV</sl-button>
                                    </sl-tooltip>
                                </div>
                            </form>
                            <ul id="menu_files_list" class="files_list">
                                <p class="include_tag hide">Files included :</p>
                            </ul>
                        </div>
                    </div>
                </sl-tab-panel>

                <!-- Kategori Panel -->
                <sl-tab-panel name="kategori">
                    <div class="container_menu">
                        <div class="table_container">
                            <div id="table_kategori"></div>
                        </div>
                        <div class="toolbar">
                            <h2>Tambah Kategori</h2>
                            <form class="form_kategori" autocomplete="off">
                                <sl-input placeholder="Label" id="tambah_kategori_label" name="label" type="text" autocomplete="off">
                                    <label slot="label">Label</label>
                                    <sl-icon name="bookmark" slot="prefix"></sl-icon>
                                </sl-input>
                                <sl-input placeholder="Nama" id="tambah_kategori_nama" name="nama" type="text" autocomplete="off">
                                    <label slot="label">Nama</label>
                                    <sl-icon name="card-text" slot="prefix"></sl-icon>
                                </sl-input>
                                <div class="form_buttons">
                                    <sl-button type="submit">
                                        <sl-icon slot="prefix" name="plus-square"></sl-icon>
                                        Tambah Kategori
                                    </sl-button>
                                    <sl-tooltip content="CSV mesti ada header dan gambar">
                                        <sl-button id="kategori_csv_input" class="csv_input">Import CSV</sl-button>
                                    </sl-tooltip>
                                </div>
                            </form>
                            <ul id="kategori_files_list" class="files_list">
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
                <sl-select placeholder="Kategori" id="edit_produk_kategori" placement="bottom">
                    <sl-icon name="tag" slot="prefix"></sl-icon>
                    <label slot="label">Kategori</label>
                    <?php loadKategoriOptionHTML(); ?>
                </sl-select>
                <sl-input placeholder="Harga" id="edit_produk_harga" name="harga" type="number" autocomplete="off">
                    <label slot="label">Harga</label>
                    <sl-icon name="cash" slot="prefix"></sl-icon>
                </sl-input>
                <sl-textarea id="edit_produk_detail" label="Detail" resize="auto"></sl-textarea>
                <sl-button id="tambah_gambar">
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
                <sl-input placeholder="Label" id="edit_kategori_label" name="label" type="text" autocomplete="off">
                    <label slot="label">Label</label>
                    <sl-icon name="bookmark" slot="prefix"></sl-icon>
                </sl-input>
                <sl-input placeholder="Nama" id="edit_kategori_nama" name="nama" type="text" autocomplete="off">
                    <label slot="label">Nama</label>
                    <sl-icon name="card-text" slot="prefix"></sl-icon>
                </sl-input>
            </form>
        </div>
        <sl-button id="edit_produk_button" class="edit_button" slot="footer">Edit</sl-button>
        <sl-button id="cancel_edit_produk_button" class="cancel_button" slot="footer" variant="danger">Cancel</sl-button>
    </sl-dialog>

    <script type="module" src="<?php echo auto_version("table_menu.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("table_kategori.js"); ?>"></script>
    <?php
    echoTabulator();
    echoShoelaceAutoloader();
    ?>
</body>

</html>

<?php
function loadKategoriOptionHTML(): void {
    global $Database;
    $array_kategori = $Database->readQuery("SELECT id, label, nama from kategori");

    foreach ($array_kategori as $kategori) {
        $id = $kategori["id"];
        $nama = $kategori["nama"];
        echo "<sl-option value='$id'>$nama</sl-option>";
    }
}
?>