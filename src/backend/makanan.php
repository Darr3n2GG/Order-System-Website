<?php
class Makanan {
    private $MySQLConnector;

    public function __construct() {
        require_once("MySQLConnector.php");
        $this->MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
    }

    public function getAllMakanan(): array {
        $arrayMakanan = $this->MySQLConnector->readQuery(
            "SELECT makanan.id, makanan.nama, kategori.label, makanan.nombor, kategori.nama AS kategori_nama, makanan.harga, makanan.gambar 
            FROM makanan INNER JOIN kategori ON makanan.kategori_id=kategori.id ORDER BY makanan.id ASC"
        );
        return $arrayMakanan;
    }
}
