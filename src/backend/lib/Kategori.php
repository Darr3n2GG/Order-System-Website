<?php

namespace lib;

require_once dirname(__FILE__, 2) . "/Database.php";

class Kategori {
    private $Database;
    public function __construct($Database) {
        $this->Database = $Database;
    }

    public function searchKategori(array $filters): array {
        $sql = "SELECT * FROM kategori WHERE 1=1";
        $types = "";
        $params = [];

        foreach ($filters as $field => $value) {
            $sql .= " AND `$field` LIKE ?";
            $types .= "s";
            $params[] = "%" . $value . "%";
        }

        return $this->Database->readQuery($sql, $types, $params);
    }

    public function addKategori(array $data): void {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = implode(", ", array_fill(0, count($data), "?"));
            $types = '';
            foreach ($data as $value) {
                $types .= $this->Database->getMysqliType($value);
            }

            $this->Database->executeQuery(
                "INSERT INTO kategori ($columns) VALUES ($placeholders)",
                $types,
                array_values($data)
            );
        } catch (\Exception $e) {
            throw new \Exception("Failed to add kategori: " . $e->getMessage(), 500);
        }
    }


    public function updateKategori(int $id, array $data): void {
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
                "UPDATE kategori SET $set WHERE id = ?",
                $types,
                $values
            );
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw new \Exception("Failed to update kategori: " . $e->getMessage(), 500);
        }
    }



    public function deleteKategori(int $id): void {
        $this->Database->executeQuery(
            "DELETE FROM kategori WHERE id = ?",
            "i",
            [$id]
        );
    }
}
