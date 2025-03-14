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

    public function getMakananFromID(int $id): array {
        $makanan = $this->MySQLConnector->readQuery(
            "SELECT makanan.id, makanan.nama, kategori.label, makanan.detail, kategori.nama AS kategori_nama, makanan.harga, makanan.gambar 
            FROM makanan INNER JOIN kategori ON makanan.id_kategori=kategori.id 
            WHERE makanan.id = ?
            ORDER BY makanan.id ASC",
            "i",
            [$id]
        );
        return $makanan[0];
    }

    public function getMakananFromKeyword(string $keyword): array {
        $format_keyword = "%$keyword%";
        $arrayMakanan = $this->MySQLConnector->readQuery(
            "SELECT makanan.id, makanan.nama, kategori.label, makanan.detail, kategori.nama AS kategori_nama, makanan.harga, makanan.gambar 
            FROM makanan INNER JOIN kategori ON makanan.id_kategori=kategori.id 
            WHERE makanan.nama LIKE ?
            ORDER BY makanan.id ASC",
            "s",
            [$format_keyword]
        );
        return $arrayMakanan;
    }
}
