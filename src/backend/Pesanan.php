<?php
class Pesanan {
    private $Database;

    public function __construct() {
        require_once(__DIR__ . "/Database.php");
        $this->Database = createDatabaseConn();
    }

    public function getSemuaPesanan(): array {
        return $this->Database->readQuery("SELECT * FROM pesanan ORDER BY id ASC");
    }

    public function getArrayPesananWhere(string $condition, string $types = "", array|null $array_of_params = null): array {
        return $this->Database->readQuery(
            "SELECT * FROM pesanan
            WHERE $condition
            ORDER BY id ASC",
            $types,
            $array_of_params
        );
    }

    public function getPesananByID(int $id): array {
        return $this->Database->readQuery(
            "SELECT * FROM pesanan WHERE id = ? ORDER BY id ASC",
            "i",
            [$id]
        );
    }

    public function getArrayPesananFromArrayID(array $array_id): array {
        $array_pesanan = [];

        foreach ($array_id as $id) {
            $pesanan = $this->getPesananByID($id);
            array_push($array_pesanan, $pesanan);
        }

        return $array_pesanan;
    }

    // Yet implemented
    public function addPesanan(
        string $nama,
        string $tarikh,
        string $cara,
        string $no_meja
    ): void {
        return;
    }
}
