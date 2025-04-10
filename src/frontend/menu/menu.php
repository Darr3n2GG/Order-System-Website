<?php
require_once(dirname(__FILE__, 3) .  "/backend/Database.php");
require_once(dirname(__FILE__, 3) . "/backend/Autoloader.php");
require_once(dirname(__FILE__, 2) . "/header/header.php");

$Database = createDatabaseConn();

$array_kategori = $Database->readQuery("SELECT kategori.label, kategori.nama from kategori");
$Produk = new lib\Produk;
$array_produk = $Produk->getSemuaProduk();
$MenuLoader = new lib\MenuLoader($array_kategori, $array_produk);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="../style.css">
    <?php echoHeaderStylesheet(); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css" />
    <script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/shoelace-autoloader.js"></script>
    <title>Menu</title>
</head>

<body>
    <?php echoHeader(); ?>

    <div class="content container">
        <div class="action_bar">
            <sl-icon-button class="cart_button icon_border" name="bag"></sl-icon-button>
            <div class="search_container">
                <sl-input class="search_bar" placeholder="Search" clearable>
                    <sl-icon name="search" slot="prefix"></sl-icon>
                </sl-input>
            </div>
            <sl-dropdown class="category_dropdown" placement="bottom-end">
                <sl-icon-button
                    class="category_button icon_border" name="list-ul" slot="trigger">
                </sl-icon-button>
                <sl-menu class="category_menu">
                    <?php $MenuLoader->displayKategoriItem(); ?>
                </sl-menu>
            </sl-dropdown>
        </div>

        <div class="menu">
            <h2 class="menu_empty hide">Tiada makanan</h2>
            <?php $MenuLoader->displayKategoriDanProduk(); ?>
        </div>

        <div class="item_dialog_container">
            <sl-dialog class="item_dialog dialog" label="">
                <img class="dialog_image" src="" alt="food image">
                <div>
                    <h2 class="dialog_price">Harga : RM</h2>
                    <h2>Description :</h2>
                    <p class="dialog_description"></p>
                </div>
                <sl-input
                    class="dialog_input" type="number" value="1" slot="footer" required>
                </sl-input>
                <sl-button class="add_item_button" value="" slot="footer" variant="primary">Add Item</sl-button>
            </sl-dialog>
        </div>

        <div class="cart">
            <sl-dialog class="cart_dialog dialog" label="Cart">
                <h1 class="cart_empty">Tiada makanan di cart</h1>
                <ul class="cart_item_list"></ul>
                <h2 class="total_price" slot="footer">Jumlah Harga : RM 0</h2>
                <sl-button class="checkout_button" slot="footer" variant="primary">Checkout</sl-button>
            </sl-dialog>
        </div>
    </div>

    <script type="module" src="menu.js"></script>
    <script type="module" src="itemDialog.js"></script>
    <script type="module" src="cart.js"></script>
    <script type="module" src="checkout.js"></script>
    <script type="module" src="search.js"></script>
    <noscript>Your browser does not support JavaScript!</noscript>
</body>

</html>