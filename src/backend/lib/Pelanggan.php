<?php

namespace lib;

require_once dirname(__FILE__, 2) . "/Database.php";

class Pelanggan {
    private $Database;

    public function __construct() {
        $this->Database = createDatabaseConn();
    }

    public function getSemuaSearchablePelanggan(): array {
        return $this->Database->readQuery(
            "SELECT id, nama, no_phone FROM pelanggan WHERE searchable = 1 ORDER BY id ASC"
        );
    }

    public function addPelanggan(string $nama, int $password, string $no_phone): void {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $this->Database->executeQuery(
            "INSERT INTO pelanggan (nama, password, no_phone) VALUES ( ?, ?, ? )",
            "sis",
            [$nama, $hashed_password, $no_phone]
        );
    }

    public function deletePelanggan(int $id): void {
        $this->Database->executeQuery(
            "DELETE FROM pelanggan WHERE id = ?",
            "i",
            [$id]
        );
    }
}
