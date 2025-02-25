<?php
require_once("../../backend/MySQLConnector.php");
require_once("../../backend/makanan.php");
require_once("MenuLoader.php");

$MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
$array_kategori = $MySQLConnector->readQuery("SELECT kategori.label, kategori.nama from kategori");
$objek_makanan = new Makanan;
$array_makanan = $objek_makanan->getAllMakanan();
$MenuLoader = new MenuLoader($array_kategori, $array_makanan);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css" />
    <script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/shoelace-autoloader.js"></script>
    <title>Menu Page</title>
</head>

<body>
    <sl-include class="include_header" src="../header/header.html"></sl-include>
    <?php
    //include_once("../header/header.php");
    ?>
    <div class="main container">
        <div class="action_bar">
            <sl-icon-button class="cart_button icon_border" name="bag"></sl-icon-button>
            <sl-input class="search" placeholder="Search" clearable>
                <sl-icon name="search" slot="prefix"></sl-icon>
            </sl-input>
            <sl-dropdown class="category_dropdown" placement="bottom-end">
                <sl-icon-button
                    class="category_button icon_border" name="list-ul" slot="trigger">
                </sl-icon-button>
                <sl-menu class="category_menu">
                    <?php
                    $MenuLoader->displayKategoriItem();
                    ?>
                </sl-menu>
            </sl-dropdown>
        </div>
        <div class="menu">
            <?php
            $MenuLoader->displayKategoriDanMakanan();
            ?>
            <sl-dialog class="item_dialog" label="">
                <img class="dialog_image" src="" alt="food image">
                <div>
                    <h2 class="dialog_price">Harga : RM</h2>
                    <h2>Description :</h2>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </div>
                <sl-input
                    class="dialog_input" type="number" value="1" slot="footer" required="true">
                </sl-input>
                <sl-button class="add_item_button" value="" slot="footer" variant="primary">Add Item</sl-button>
            </sl-dialog>
        </div>
        <div class="cart">
            <sl-dialog class="cart_dialog" label="Cart">
                <ul class="item_list">
                </ul>
                <sl-button class="checkout_button" slot="footer" variant="primary">Checkout</sl-button>
            </sl-dialog>
        </div>
    </div>
    <script type="module" src="menu.js"></script>
    <script type="module" src="itemDialog.js"></script>
    <script type="module" src="cart.js"></script>
</body>

</html>