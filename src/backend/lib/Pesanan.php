<?php

namespace lib;

require_once dirname(__FILE__, 2) . "/Database.php";
require_once dirname(__FILE__, 2) . "/Masa.php";

class Pesanan {
    private $Database;
    public function __construct($Database) {
        $this->Database = $Database;
    }

    public function searchPesanan(array $filters): array {
        $sql = "SELECT 
        pesanan.id AS id, 
        pesanan.id_pelanggan AS id_pelanggan, 
        pelanggan.nama AS nama, 
        pesanan.tarikh AS tarikh,
        pesanan.id_status AS id_status, 
        status.status AS status, 
        pesanan.cara AS cara, 
        pesanan.no_meja AS no_meja,
        COALESCE(SUM(belian.kuantiti * produk.harga), 0) AS jumlah_harga # Jika kuantiti * harga kembali null, guna 0.
        FROM pesanan
        INNER JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id
        INNER JOIN status ON pesanan.id_status = status.id
        LEFT JOIN belian ON pesanan.id = belian.id_pesanan
        LEFT JOIN produk ON belian.id_produk = produk.id
        WHERE 1=1";

        $types = "";
        $params = [];

        // Handle week
        if (!empty($filters['range']) && $filters['range'] === 'week') {
            $filters['from'] = getWeekStart();
            $filters['to'] = getWeekEnd();
        }

        // Handle special date filters first
        if (!empty($filters['from'])) {
            $sql .= " AND pesanan.tarikh >= ?";
            $types .= "s";
            $params[] = $filters['from'];
        }

        if (!empty($filters['to'])) {
            $sql .= " AND pesanan.tarikh <= ?";
            $types .= "s";
            $params[] = $filters['to'];
        }

        // Remove the date keys to avoid re-processing
        unset($filters['from'], $filters['to'], $filters['range'], $filters['week']);

        foreach ($filters as $field => $value) {
            if (strtoupper($field) === "NAMA") {
                $field = "pelanggan.nama";
            }

            if (strpos($field, '.') === false) {
                $sql .= " AND `$field` LIKE ?";
            } else {
                $sql .= " AND $field LIKE ?"; // When have . cannot use ``
            }
            $types .= "s";
            $params[] = "%" . $value . "%";
        }
        $sql .= " GROUP BY 
            pesanan.id, 
            pesanan.id_pelanggan, 
            pelanggan.nama, 
            pesanan.tarikh,
            pesanan.id_status, 
            status.status, 
            pesanan.cara, 
            pesanan.no_meja";

        return $this->Database->readQuery($sql, $types, $params);
    }

    public function addPesanan(array $data): void {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_fill(0, count($data), "?"));
            $types = '';
            foreach ($data as $value) {
                $types .= $this->Database->getMysqliType($value);
            }

            $this->Database->executeQuery(
                "INSERT INTO pesanan ($columns) VALUES ($placeholders)",
                $types,
                array_values($data)
            );
        } catch (\Exception $e) {
            throw new \Exception("Failed to add pesanan: " . $e->getMessage(), 500);
        }
    }


    public function updatePesanan(int $id, array $data): void {
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
                "UPDATE pesanan SET $set WHERE id = ?",
                $types,
                $values
            );
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw new \Exception("Failed to update pesanan: " . $e->getMessage(), 500);
        }
    }

    public function getLastInsertedIDOfPesanan(): int {
        return $this->Database->readLastInsertedID();
    }

    public function deletePesanan(int $id): void {
        $this->Database->executeQuery(
            "DELETE FROM pesanan WHERE id = ?",
            "i",
            [$id]
        );
    }

    public function getStatus(): array {
        $sql = "SELECT * FROM status WHERE 1=1";
        $types = "";
        $params = [];
        return $this->Database->readQuery($sql, $types, $params);
    }
    public function getMeja(): array {
        $sql = "SELECT * FROM meja WHERE 1=1";
        $types = "";
        $params = [];
        return $this->Database->readQuery($sql, $types, $params);
    }

    public function getReceipt(int $pesananId): ?array {
        $summaryList = $this->searchPesanan(['pesanan.id' => $pesananId]);
        if (empty($summaryList)) return null;

        $pesanan = $summaryList[0];

        // Get belian items
        $sql = "SELECT 
                    belian.id AS id_belian,
                    belian.kuantiti,
                    produk.nama,
                    produk.harga,
                    (belian.kuantiti * produk.harga) AS jumlah
                FROM belian
                INNER JOIN produk ON belian.id_produk = produk.id
                WHERE belian.id_pesanan = ?";

        $belianItems = $this->Database->readQuery($sql, "i", [$pesananId]);

        $pesanan['belian'] = $belianItems;

        return $pesanan;
    }

}
