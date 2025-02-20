<?php
class Makanan {
    private $MySQLConnector;

    public function __construct() {
        require_once("MySQLConnector.php");
        $this->MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
    }

    public function getAllMakanan(): array {
        $arrayMakanan = $this->MySQLConnector->readQuery(
            "SELECT makanan.id, makanan.nama, kategori.label, makanan.detail, kategori.nama AS kategori_nama, makanan.harga, makanan.gambar 
            FROM makanan INNER JOIN kategori ON makanan.id_kategori=kategori.id ORDER BY makanan.id ASC"
        );
        return $arrayMakanan;
    }

    public function getMakanan(int $id): array {
        $makanan = $this->MySQLConnector->readQuery(
            "SELECT makanan.nama, kategori.label, makanan.detail, kategori.nama AS kategori_nama, makanan.harga, makanan.gambar 
            FROM makanan INNER JOIN kategori ON makanan.id_kategori=kategori.id 
            WHERE makanan.id = $id
            ORDER BY makanan.id ASC"
        );
        return $makanan;
    }
}
