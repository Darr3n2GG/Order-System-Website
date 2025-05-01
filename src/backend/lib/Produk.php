<?php

namespace lib;

require_once dirname(__FILE__, 2) . "/Database.php";

class Produk {
    private $Database;

    public function __construct() {
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

    public function insertProduk($nama, $kategori, $harga, $detail, $gambar = '/'): bool {
        return $this->Database->executeQuery(
            "INSERT INTO produk (nama, id_kategori, harga, detail, gambar) VALUES (?, ?, ?, ?, ?)",
            "ssdss",
            [$nama, $kategori, $harga, $detail, $gambar]
        );
    }
    
    public function updateProduk(int $id, array $data): void {
        $fields = [];
        $values = [];
        $types = "";

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
            $types .= $this->Database->getMysqliType($value);
        }
        $set = implode(', ', $fields);
        $values[] = $id;
        $types .= "i";

        $this->Database->executeQuery(
            "UPDATE produk SET $set WHERE id = ?",
            $types,
            $values
        );
    }

    public function deleteProduk(int $id): void {
        $this->Database->executeQuery(
            "DELETE FROM produk WHERE id = ?",
            "i",
            [$id]
        );
    }
}
