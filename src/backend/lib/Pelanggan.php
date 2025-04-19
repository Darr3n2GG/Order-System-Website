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

    public function findPelanggan(string $id): array {
        return $this->Database->readQuery(
            "SELECT id, nama, no_phone FROM pelanggan WHERE searchable = 1 AND id = ?",
            "i",
            [$id]
        );
    }

    public function checkPelangganExists(string $id): bool {
        $stmt = $this->Database->prepareStatement("SELECT id FROM pelanggan WHERE searchable = 1 AND id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        return ($stmt->num_rows > 0);
    }

    public function addPelanggan(string $nama, string $password, string $no_phone): void {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $this->Database->executeQuery(
            "INSERT INTO pelanggan (nama, password, no_phone) VALUES ( ?, ?, ? )",
            "sss",
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
