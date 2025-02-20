<?php
require_once("makanan.php");

try {
    $makanan_id = isset($_GET["id"]) ? $_GET["id"] : 0;
    $objek_makanan = new Makanan;
    $makanan = $objek_makanan->getMakanan($makanan_id);
    echo json_encode($makanan);
} catch (Exception $e) {
    echo "Message : " . $e->getMessage();
}
