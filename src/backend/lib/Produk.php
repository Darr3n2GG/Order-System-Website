<?php

namespace lib;

require_once dirname(__FILE__, 2) . "/Database.php";

class Produk {
    private $Database;
    public function __construct($Database) {
        $this->Database = $Database;
    }

    public function searchProduk(array $filters): array {
        $sql = "SELECT 
        produk.id AS id, 
        produk.nama AS nama, 
        kategori.label AS label,
        produk.maklumat AS maklumat, 
        kategori.id AS id_kategori,
        kategori.nama AS kategori_nama, 
        produk.harga AS harga, 
        produk.gambar AS gambar
        FROM produk
        INNER JOIN kategori ON produk.id_kategori=kategori.id
        WHERE 1=1";
        $types = "";
        $params = [];

        foreach ($filters as $field => $value) {
            if (strtoupper($field) === "NAMA") {
                $field = "produk.nama";
                $sql .= " AND $field LIKE ?";
                $types .= "s";
                $params[] = "%" . $value . "%";
            } elseif (strtoupper($field) === "ID") {
                $field = "produk.id";
                $sql .= " AND $field = ?";
                $types .= "i";
                $params[] = $value;
            } else {
                // Generic fallback for other fields
                if (strpos($field, '.') === false) {
                    $sql .= " AND `$field` LIKE ?";
                } else {
                    $sql .= " AND $field LIKE ?"; // When have . cannot use ``
                }
                $types .= "s";
            }
        }

        // Add ordering at the end of the query
        $sql .= " ORDER BY produk.id ASC";

        return $this->Database->readQuery($sql, $types, $params);
    }

    public function addProduk(array $data): void {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_fill(0, count($data), "?"));
            $types = '';
            foreach ($data as $value) {
                $types .= $this->Database->getMysqliType($value);
            }

            $this->Database->executeQuery(
                "INSERT INTO produk ($columns) VALUES ($placeholders)",
                $types,
                array_values($data)
            );
        } catch (\Exception $e) {
            throw new \Exception("Failed to add produk]: " . $e->getMessage(), 500);
        }
    }


    public function updateProduk(int $id, array $data): void {
        $fields = [];
        $values = [];
        $types = "";

        foreach ($data as $key => $value) {
            // Skip fields that should never be updated directly
            if ($key === "id") {
                continue;
            }

            $fields[] = "$key = ?";
            $values[] = $value;
            $types .= $this->Database->getMysqliType($value);
        }

        if (empty($fields)) {
            throw new \Exception("No valid fields provided to update.", 400);
        }

        $set = implode(', ', $fields);
        $values[] = $id;
        $types .= "i";

        try {
            $this->Database->executeQuery(
                "UPDATE produk SET $set WHERE id = ?",
                $types,
                $values
            );
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw new \Exception("Failed to update produk: " . $e->getMessage(), 500);
        }
    }

    public function deleteProduk(int $id): void {
        $this->Database->executeQuery(
            "DELETE FROM produk WHERE id = ?",
            "i",
            [$id]
        );
    }

    public function uploadImage(array $file): string {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("Image upload failed with error: " . $file["error"], 422);
        }

        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/Order-System-Website/src/assets/produk/";

        // if (!is_dir($targetDir)) {
        //     mkdir($targetDir, 0755, true);
        // }

        $filename = basename($file["name"]);
        $targetPath = $targetDir . $filename;

        if (!move_uploaded_file($file["tmp_name"], $targetPath)) {
            throw new \Exception("Failed to save uploaded image.", 500);
        }

        // Return web-accessible path
        return "/Order-System-Website/src/assets/produk/" . $filename;
    }
}
