<?php
require_once("../../backend/MySQLConnector.php");
require_once("../../backend/makanan.php");

$MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
$array_kategori = $MySQLConnector->readQuery("SELECT kategori.label, kategori.nama from kategori");
$objek_makanan = new Makanan;
$array_makanan = $objek_makanan->getAllMakanan();
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
    <sl-include src="../header/header.html"></sl-include>
    <div class="main container">
        <div class="action_bar" id="action-bar">
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
                    foreach ($array_kategori as $kategori) {
                        $label = $kategori["label"];
                        $nama = $kategori["nama"];
                        echo "<sl-menu-item value='$label'>$nama</sl-menu-item>";
                    }
                    ?>
                </sl-menu>
            </sl-dropdown>
        </div>
        <div class="menu">
            <?php
            foreach ($array_kategori as $kategori) {
                $label = $kategori["label"];
                $nama = $kategori["nama"];
                $array_makanan_dlm_kategori = [];
                foreach ($array_makanan as $makanan) {
                    if ($makanan["kategori_nama"] == $nama) {
                        array_push($array_makanan_dlm_kategori, $makanan);
                        $key = array_search($makanan, $array_makanan);
                        array_splice($array_makanan, $key, 1);
                    }
                }
                echo "<div class='kategori' id='$label'><h1>$nama</h1>";
                foreach ($array_makanan_dlm_kategori as $makanan) {
                    $gambar = $makanan["gambar"];
                    $nama = $makanan["nama"];
                    $id = $makanan["id"];
                    $label = $makanan["label"] . $id;
                    $harga = $makanan["harga"];
                    echo "<div class='food_item' id='$id'>";
                    echo "  <img src='$gambar' alt='$nama'>";
                    echo "  <div class='food_info'>";
                    echo "      <div class='food_row'>";
                    echo "          <h2>$nama</h2>";
                    echo "          <sl-tag size='small' pill>$label</sl-tag>";
                    echo "      </div>";
                    echo "      <div class='food_row'>";
                    echo "          <p><strong>Harga : RM $harga</strong></p>";
                    echo "      </div>";
                    echo "  </div>";
                    echo "</div>";
                }
                echo "</div>";
            }
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