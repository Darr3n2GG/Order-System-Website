<?php

namespace lib;

require_once dirname(__FILE__, 2) . "/Database.php";

class Belian {
    private $Database;

    public function __construct() {
        $this->Database = createDatabaseConn();
    }

    public function getBelianFromID(int $id): array {
        return $this->Database->readQuery(
            "SELECT * FROM belian WHERE id = ? ORDER BY id ASC",
            "i",
            [$id]
        );
    }

    public function getBelianFromIDPesanan(int $id_pesanan): array {
        return $this->Database->readQuery(
            "SELECT * FROM belian WHERE id_pesanan = ? ORDER BY id ASC",
            "i",
            [$id_pesanan]
        );
    }

    public function addBelian(int $id_pesanan, array $cart_assoc_array): void {
        $stmt = $this->Database->prepareStatement("INSERT INTO belian (id_pesanan, id_produk, kuantiti) VALUES (?, ?, ?)");
        foreach ($cart_assoc_array as $cart_item) {
            $stmt->bind_param("iii", $id_pesanan, $cart_item["id"], $cart_item["kuantiti"]);
            $stmt->execute();
        }
        $stmt->close();
    }
}
