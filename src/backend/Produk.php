<?php
class Produk {
    private $Database;

    public function __construct() {
        require_once(__DIR__ . "/Database.php");
        $this->Database = createDatabaseConn();
    }

    public function getSemuaProduk(): array {
        $arrayMakanan = $this->Database->readQuery(
            "SELECT produk.id, produk.nama, kategori.label, produk.detail, kategori.nama AS kategori_nama, produk.harga, produk.gambar 
            FROM produk INNER JOIN kategori ON produk.id_kategori=kategori.id ORDER BY produk.id ASC"
        );
        return $arrayMakanan;
    }

    public function getProdukFromID(int $id): array {
        $produk = $this->Database->readQuery(
            "SELECT produk.id, produk.nama, kategori.label, produk.detail, kategori.nama AS kategori_nama, produk.harga, produk.gambar 
            FROM produk INNER JOIN kategori ON produk.id_kategori=kategori.id 
            WHERE produk.id = ?
            ORDER BY produk.id ASC",
            "i",
            [$id]
        );
        return $produk[0];
    }

    public function getProdukFromKeyword(string $keyword): array {
        $format_keyword = "%$keyword%";
        $arrayProduk = $this->Database->readQuery(
            "SELECT produk.id, produk.nama, kategori.label, produk.detail, kategori.nama AS kategori_nama, produk.harga, produk.gambar 
            FROM produk INNER JOIN kategori ON produk.id_kategori=kategori.id 
            WHERE produk.nama LIKE ?
            ORDER BY produk.id ASC",
            "s",
            [$format_keyword]
        );
        return $arrayProduk;
    }
}
