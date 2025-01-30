<?php
require_once("makanan.php");

try {
    $makanan = new Makanan;
    $array_makanan = $makanan->getAllMakanan();
    echo json_encode($array_makanan);
} catch (Exception $e) {
    echo "Message : " . $e->getMessage();
}
