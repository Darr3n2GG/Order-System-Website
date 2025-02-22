<?php
require_once("MySQLConnector.php");
require_once("makanan.php");

try {
    $MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
    $kategori = $MySQLConnector->readQuery("SELECT kategori.label, kategori.nama from kategori");
    $objek_makanan = new Makanan;
    $array_makanan = $objek_makanan->getAllMakanan();
    $data = ["kategori" => $kategori, "makanan" => $array_makanan];
    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Message : " . $e->getMessage()]);
}
