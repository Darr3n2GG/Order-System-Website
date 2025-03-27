<?php
class Belian {
    private $Database;

    public function __construct() {
        require_once(__DIR__ . "/Database.php");
        $this->Database = createDatabaseConn();
    }

    public function getBelianFromID(int $id): array {
        return $this->Database->readQuery(
            "SELECT * FROM belian WHERE id = ? ORDER BY id ASC",
            "i",
            [$id]
        );
    }

    public function getArrayBelianFromArrayIDPesanan(array $array_id_pesanan): array {
        $in = join(',', array_fill(0, count($array_id_pesanan), '?'));

        return $this->Database->readQuery(
            "SELECT * FROM belian WHERE id_pesanan IN ( $in ) ORDER BY id ASC",
            str_repeat('i', count($array_id_pesanan)),
            $array_id_pesanan
        );
    }
}
