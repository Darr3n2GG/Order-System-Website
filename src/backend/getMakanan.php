<?php
require_once("makanan.php");

function setup(): void {
    require("rb-mysql.php");
    R::setup(
        "mysql:host=localhost;dbname=restorandb",
        "root",
        ""
    );
}

function getMakanan() {
    $arrayMakanan = R::findAll("makanan");
    return $arrayMakanan;
}

function main() {
    try {
        $makanan = new Makanan;
        $arrayMakanan = $makanan->getAllMakananString();
        print($arrayMakanan);
    } catch (Exception $e) {
        echo $e;
    }
}

main();
