<?php
require_once dirname(__FILE__, 3) .  "/backend/Database.php";
require_once dirname(__FILE__, 3) . "/backend/Autoloader.php";
require_once dirname(__FILE__, 2) . "/header/header.php";
require_once dirname(__FILE__, 2) . "/dependencies.php";

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
    <link rel="stylesheet" href="<?php echo auto_version("menu.css"); ?>">
    <link rel="stylesheet" href="<?php echo auto_version("../style.css"); ?>">
    <?php
    echoHeaderStylesheet();
    echoShoelaceStyle();
    ?>
    <title>Menu</title>
</head>

<body>
    <?php echoHeader(); ?>

    <div class="content container">
        <div class="left_content">
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
                    <sl-input class="search_bar" placeholder="Search" clearable>
                        <sl-icon name="search" slot="prefix"></sl-icon>
                    </sl-input>
                </div>
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
                <div class="cart_item">
                    <img class="imej_item" src="../../assets/produk/1.png" alt="">
                    <span class="item_name">Burger</span>
                    <span class="item_price">$5.99</span>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <sl-button id="decrement" size="small" variant="default">−</sl-button>
                        <sl-input id="spinbox" type="number" value="0" style="width: 100px;"></sl-input>
                        <sl-button id="increment" size="small" variant="default">+</sl-button>
                    </div>
                </div>
                <div class="cart_item">
                    <span class="item_name">🍔 Burger</span>
                    <span class="item_price">$5.99</span>
                </div>
                <div class="cart_item">
                    <span class="item_name">🍔 Burger</span>
                    <span class="item_price">$5.99</span>
                </div>
                <div class="cart_item">
                    <span class="item_name">🍔 Burger</span>
                    <span class="item_price">$5.99</span>
                </div>
            </div>
            <footer class="cart_summary">
                <p>Total: $10.97</p>
                <sl-button>Checkout</sl-button>
            </footer>
        </aside>
    </div>

    <sl-dialog class="item_dialog" label="">
        <img class="dialog_image" src="" alt="food image">
        <div>
            <h2 class="dialog_price">Harga : RM</h2>
            <h2>Description :</h2>
            <span class="dialog_description"></span>
        </div>
        <div class="dialog_kuantiti" slot="footer">
            <sl-button size="small">
                <sl-icon name="dash-square"></sl-icon>
            </sl-button>
            <sl-input
                class="dialog_input" type="number" value="1" required>
            </sl-input>
            <sl-button size="small">
                <sl-icon name="plus-square"></sl-icon>
            </sl-button>
        </div>
        <sl-button class="add_item_button" value="" slot="footer" variant="primary">Add Item</sl-button>
    </sl-dialog>

    <sl-dialog class="cart_dialog" label="Cart">
        <h1 class="cart_dialog_empty">Tiada makanan di cart</h1>
        <div class="cart_dialog_items"></div>
        <h2 class="cart_dialog_total_price" slot="footer">Jumlah Harga : RM 0</h2>
        <sl-button class="checkout_button" slot="footer" variant="primary">Checkout</sl-button>
    </sl-dialog>

    <script type="module" src="<?php echo auto_version("menu.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("itemDialog.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("cart.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("checkout.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("search.js"); ?>"></script>
    <?php
    echoShoelaceAutoloader();
    echoNoScript();
    ?>
</body>

</html>