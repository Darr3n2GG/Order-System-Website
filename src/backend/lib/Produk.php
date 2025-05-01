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
        // Prepare SQL query
        $query = "INSERT INTO produk (nama, id_kategori, harga, detail, gambar) 
                  VALUES (?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $this->Database->prepareStatement($query);

        // Bind parameters (note that we have 5 parameters and 5 placeholders in the query)
        // "ssdss" -> s for string (nama, kategori), d for double (harga), s for string (detail), s for string (gambar)
        $stmt->bind_param("ssdss", $nama, $kategori, $harga, $detail, $gambar);

        // Execute the query
        return $stmt->execute();
    }

}
