<?php

namespace lib;

require_once dirname(__FILE__, 2) . "/Database.php";

class Kategori {
    private $Database;

    public function __construct() {
        $this->Database = createDatabaseConn();
    }

    public function getSemuaKategori(): array {
        $arrayKategori = $this->Database->readQuery(
            "SELECT id, label, nama from kategori"
        );
        return $arrayKategori;
    }

    public function insertKategori($nama, $label): bool {
        return $this->Database->executeQuery(
            "INSERT INTO kategori (nama, label) VALUES (?, ?)",
            "ss",
            [$nama, $label]
        );
    }
    
    public function updateKategori(int $id, array $data): void {
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
            "UPDATE kategori SET $set WHERE id = ?",
            $types,
            $values
        );
    }

    public function deleteKategori(int $id): bool {
        return $this->Database->executeQuery(
            "DELETE FROM kategori WHERE id = ?",
            "i",
            [$id]
        );
    }
 }
