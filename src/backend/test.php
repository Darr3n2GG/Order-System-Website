<?php

use lib\Pelanggan;

require_once "Autoloader.php";

$pelanggan = new Pelanggan;

$data = [
    "password" => "0000000"
];

$pelanggan->updatePelanggan(20, $data);
