<?php
require_once("MySQLConnector.php");

try {
    $MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
    $kategori = $MySQLConnector->readQuery("SELECT kategori.label, kategori.nama from kategori");
    echo json_encode($kategori);
} catch (Exception $e) {
    echo "Message : " . $e->getMessage();
}
