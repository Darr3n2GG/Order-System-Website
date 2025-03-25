<?php
header("Content-Type: application/json");

require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/JsonResponseHandler.php");
require_once(__DIR__ . "/ErrorHandler.php");
require_once(__DIR__ . "/Masa.php");

$Database = createDatabaseConn();

/**
 * RevenueAPI returns an array of total revenue based on a weekly basis.
 */

$array_pesanan = getArrayPesananThisWeek();

// The array is arranged like this: [sunday, monday, tuesday, wednesday, thursday, friday, saturday]
$array_jumlah_harga_by_day = [0, 0, 0, 0, 0, 0, 0];

$jumlah_harga = 0.0;

/**
 * It gets the price of the product ordered and the quantity of that ordered product
 * and multiplies it to get the total revenue for that order.
 */

foreach ($array_pesanan as $pesanan) {
    $jumlah_harga_pesanan = 0;
    $array_belian = getArrayBelianFromID($pesanan["id"]);

    foreach ($array_belian as $belian) {
        $harga = getHargaFromIDProduk($belian["id_produk"]);
        $kuantiti = $belian["kuantiti"];

        $jumlah_harga_pesanan += $harga * $kuantiti;
    }

    /**
     * arrange income based on when the order was added
     */

    $current_day = strtotime($pesanan["tarikh"]);
    $week_start = strtotime(getWeekStart());

    $day = ($current_day - $week_start) / SECONDS_IN_A_DAY; // Seconds in a day

    $array_jumlah_harga_by_day[$day] += $jumlah_harga_pesanan;
}

echoJsonResponse(true, "RevenueAPI request processed", ["data" => $array_jumlah_harga_by_day, "total" => $jumlah_harga]);

function getArrayPesananThisWeek(): array {
    global $Database;

    $week_start = getWeekStart();
    $week_end = getWeekEnd();

    return $Database->readQuery(
        "SELECT id, tarikh FROM pesanan
        WHERE tarikh >= ? and tarikh < ?
        ORDER BY tarikh ASC",
        "ss",
        [$week_start, $week_end]
    );
}

function getArrayBelianFromID(int $id): array {
    global $Database;
    return $Database->readQuery(
        "SELECT id_produk, kuantiti
        FROM belian
        WHERE id_pesanan = ?",
        "i",
        [$id]
    );
}

function getHargaFromIDProduk($id): int {
    global $Database;
    $harga_dalam_array = $Database->readQuery(
        "SELECT harga FROM produk
        WHERE id = ?",
        "i",
        [$id]
    );
    return $harga_dalam_array[0]["harga"];
}
