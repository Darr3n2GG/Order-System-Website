<?php
require_once("MySQLConnector.php");
require_once("makanan.php");

try {
    $MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
    $kategori = $MySQLConnector->readQuery("SELECT kategori.label, kategori.nama from kategori");
    $makanan = new Makanan;
    $array_makanan = $makanan->getAllMakanan();

    $data = ["kategori" => $kategori, "makanan" => $array_makanan];
    echo json_encode($data);
} catch (Exception $e) {
    echo "Message : " . $e->getMessage();
}
