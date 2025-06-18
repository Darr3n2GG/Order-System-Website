<?php
require_once dirname(__FILE__, 3) . "/backend/Database.php";
require_once dirname(__FILE__, 3) . "/backend/Autoloader.php";
require_once dirname(__FILE__, 2) . "/header/header.php";
require_once dirname(__FILE__, 2) . "/dependencies.php";
require_once dirname(__FILE__, 3) . "/scripts/MenuLoader.php";

$Session = new lib\Session;
if ($Session->isAdmin()) {
    echo "<script type='text/javascript'>
            alert('Sila log keluar dari akaun admin untuk mengguna menu.');
            window.location.href = '/Order-System-Website/src/frontend/admin/dashboard/dashboard.php';
        </script>";
}

$Database = createDatabaseConn();

$array_kategori = $Database->readQuery("SELECT kategori.label, kategori.nama from kategori");
$Produk = new lib\Produk($Database);
$filters = [];
$array_produk = $Produk->searchProduk($filters);
$MenuLoader = new MenuLoader($array_kategori, $array_produk);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo auto_version("menu.css"); ?>">
    <link rel="stylesheet" href="<?php echo auto_version("cart.css"); ?>">
    <link rel="stylesheet" href="<?php echo auto_version("../style.css"); ?>">
    <link rel="stylesheet" href="<?php echo auto_version("../spinbox/spinbox.css"); ?>">
    <?php
    echoHeaderStylesheet();
    echoShoelaceStyle();
    ?>
    <title>Menu</title>
</head>

<body>
    <?php echoHeader(); ?>
    <div class="content container">
        <div class="main_content">
            <div class="action_bar">
                <sl-dropdown class="category_dropdown" placement="bottom-start">
                    <sl-icon-button
                        class="category_button icon_border" name="list-ul" slot="trigger">
                    </sl-icon-button>
                    <sl-menu class="category_menu">
                        <?php $MenuLoader->displayKategoriItem(); ?>
                    </sl-menu>
                </sl-dropdown>
                <div class="search_container">
                    <sl-input class="search_bar" placeholder="Cari Makanan..." clearable>
                        <sl-icon name="search" slot="prefix"></sl-icon>
                    </sl-input>
                </div>
                <sl-button class="cari_button">Cari</sl-button>
                <sl-icon-button class="cart_button icon_border" name="bag"></sl-icon-button>
            </div>

            <div class="menu">
                <h2 class="menu_empty hide">Tiada makanan</h2>
                <?php $MenuLoader->displayKategoriDanProduk(); ?>
            </div>
        </div>

        <aside class="cart_container">
            <h2>Cart</h2>
            <div class="cart_items">
                <h2 class="cart_empty">Tiada makanan di cart</h2>
            </div>
            <footer class="cart_summary">
                <p class="cart_total_price">Jumlah Harga : RM 0</p>
                <sl-button class="checkout_button" variant="primary">Checkout</sl-button>
            </footer>
        </aside>
    </div>

    <sl-dialog class="item_dialog" label="">
        <img class="dialog_image" src="" alt="food image">
        <div>
            <h2 class="dialog_price">Harga : RM</h2>
            <h2>Maklumat :</h2>
            <span class="dialog_description"></span>
        </div>
        <sl-button-group class="spinbox" slot="footer">
            <sl-button class="spinbox_decrement" variant="default" size="medium" pill>
                <sl-icon name="dash-lg"></sl-icon>
            </sl-button>
            <sl-input class="spinbox_input dialog_input" type="number" value="1" size="medium" no-spin-buttons></sl-input>
            <sl-button class="spinbox_increment" variant="default" size="medium" pill>
                <sl-icon name="plus-lg"></sl-icon>
            </sl-button>
        </sl-button-group>
        <sl-button class="add_item_button" value="" slot="footer" variant="primary">Add Item</sl-button>
    </sl-dialog>

    <sl-dialog class="cart_dialog" label="Cart">
        <h1 class="cart_dialog_empty">Tiada makanan di cart</h1>
        <div class="cart_dialog_items"></div>
        <h2 class="cart_dialog_total_price" slot="footer">Jumlah Harga : RM 0</h2>
        <sl-button class="dialog_checkout_button" slot="footer" variant="primary">Checkout</sl-button>
    </sl-dialog>

    <div id="receiptModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); width:80%; max-width:600px; background:white; padding:20px; border:1px solid #ccc; box-shadow:0 0 10px rgba(0,0,0,0.3); z-index:9999;">
        <div style="text-align:right;">
            <button id="cetak_button">Cetak</button>
            <button id="close_receipt_button">Tutup</button>
        </div>
        <iframe id="receiptFrame" src="" style="width:100%; height:400px; border:none;"></iframe>
    </div>

    <script type="module" src="<?php echo auto_version("menu.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("itemDialog.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("cart.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("checkout.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("search.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("../spinbox/spinbox.js"); ?>"></script>
    <?php
    echoShoelaceAutoloader();
    echoNoScript();
    ?>
</body>

</html>