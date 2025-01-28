<?php
class Makanan {
    public function __construct() {
        require("rb-mysql.php");
        R::setup(
            "mysql:host=localhost;dbname=restorandb",
            "root",
            ""
        );
    }

    private function getMakanan(): array {
        $arrayMakanan = R::findAll("makanan");
        return $arrayMakanan;
    }

    public function getAllMakananString(): string {
        try {
            $arrayMakanan = $this->getMakanan();
            $arrayMakanan = implode(',', $arrayMakanan);
            return $arrayMakanan;
        } catch (Exception $e) {
            echo $e;
        }
    }
}
