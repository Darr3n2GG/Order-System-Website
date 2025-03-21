<?php
header("Content-Type: application/json");

require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/JsonResponseHandler.php");
require_once(__DIR__ . "/ErrorHandler.php");

$Database = DatabaseFactory();

$day = date("w");
$week_start = date(DATE_FORMAT, strtotime("-" . $day . " days"));
$week_end = date(DATE_FORMAT, strtotime("+" . (6 - $day) . " days"));

$array_pesanan = $Database->readQuery(
    "SELECT id, tarikh FROM pesanan
    WHERE tarikh >= ? and tarikh < ?
    ORDER BY id ASC",
    "ss",
    [$week_start, $week_end]
);

var_dump($array_pesanan);

$array_jumlah_harga = [];
$jumlah_harga = 0.0;

foreach ($array_pesanan as $pesanan) {
    $array_belian = $Database->readQuery(
        "SELECT id_produk, kuantiti
        FROM belian
        WHERE id_pesanan = ?",
        "i",
        [$pesanan["id"]]
    );

    var_dump($array_belian);

    foreach ($array_belian as $belian) {
        $formatted_harga = $Database->readQuery(
            "SELECT harga FROM produk
            WHERE id = ?",
            "i",
            [$belian["id_produk"]]
        );

        $harga = $formatted_harga[0]["harga"];
        $kuantiti = $belian["kuantiti"];

        $jumlah_harga_belian = $harga * $kuantiti;
        array_push($array_jumlah_harga, [$jumlah_harga_belian]);
        $jumlah_harga += $jumlah_harga_belian;
        echo $jumlah_harga_belian . ",";
    }
}

echo $jumlah_harga;
