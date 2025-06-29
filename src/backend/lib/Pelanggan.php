<?php

namespace lib;

require_once dirname(__FILE__, 2) . "/Database.php";

class Pelanggan {
    private $Database;
    public function __construct($Database) {
        $this->Database = $Database;
    }

    public function searchPelanggan(array $filters): array {
        $sql = "SELECT * FROM pelanggan WHERE 1=1";
        $types = "";
        $params = [];

        foreach ($filters as $field => $value) {
            $sql .= " AND `$field` LIKE ?";
            $types .= "s";
            $params[] = "%" . $value . "%";
        }

        return $this->Database->readQuery($sql, $types, $params);
    }

    public function addPelanggan(array $data): void {
        try {
            // Hash password if present
            if (isset($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_fill(0, count($data), "?"));
            $types = '';
            foreach ($data as $value) {
                $types .= $this->Database->getMysqliType($value);
            }

            $this->Database->executeQuery(
                "INSERT INTO pelanggan ($columns) VALUES ($placeholders)",
                $types,
                array_values($data)
            );
        } catch (\Exception $e) {
            throw new \Exception("Pelanggan gagal ditambah: " . $e->getMessage(), 500);
        }
    }


    public function updatePelanggan(int $id, array $data): void {
        $fields = [];
        $values = [];
        $types = "";

        foreach ($data as $key => $value) {
            // Skip fields that should never be updated directly
            if ($key === "id") {
                continue;
            }

            $fields[] = "$key = ?";
            $values[] = ($key === "password") ? password_hash($value, PASSWORD_DEFAULT) : $value;
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
                "UPDATE pelanggan SET $set WHERE id = ?",
                $types,
                $values
            );
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw new \Exception("Failed to update pelanggan: " . $e->getMessage(), 500);
        }
    }



    public function deletePelanggan(int $id): void {
        $this->Database->executeQuery(
            "DELETE FROM pelanggan WHERE id = ?",
            "i",
            [$id]
        );
    }

    public function findPelanggan(string $id): array {
        return $this->Database->readQuery(
            "SELECT pelanggan.id, pelanggan.nama, pelanggan.no_phone, tahap.tahap
            FROM pelanggan INNER JOIN tahap ON pelanggan.tahap = tahap.id
            WHERE pelanggan.searchable = 1 AND pelanggan.id = ?",
            "i",
            [$id]
        )[0];
    }

    public function checkPelangganExists(string $id): bool {
        $stmt = $this->Database->prepareStatement("SELECT id FROM pelanggan WHERE searchable = 1 AND id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        return ($stmt->num_rows > 0);
    }

    public function getTahapPelanggan(string $id): string {
        return $this->Database->readQuery(
            "SELECT tahap.tahap AS tahap 
            FROM pelanggan INNER JOIN tahap ON pelanggan.tahap = tahap.id
            WHERE pelanggan.id = ?",
            "i",
            [$id]
        )[0]["tahap"];
    }
}
