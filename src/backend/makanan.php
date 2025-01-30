<?php
class Makanan {
    private $MySQLConnector;

    public function __construct() {
        require_once("MySQLConnector.php");
        $this->MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
    }

    public function getAllMakanan(): array {
        $arrayMakanan = $this->MySQLConnector->readQuery("SELECT * FROM makanan ORDER BY id ASC");
        return $arrayMakanan;
    }
}
