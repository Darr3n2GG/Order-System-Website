<?php
header("Content-Type: application/json");

require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/JsonResponseHandler.php");
require_once(__DIR__ . "/ErrorHandler.php");

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
    $array_belian = getArrayBelianFromID($pesanan["id"]);

    foreach ($array_belian as $belian) {
        $harga = getHargaFromIDProduk($belian["id_produk"]);
        $kuantiti = $belian["kuantiti"];

        $jumlah_harga_belian = $harga * $kuantiti;

        $jumlah_harga += $jumlah_harga_belian;
    }

    /**
     * arrange income based on when the order was added
     */

    define("SECONDS_IN_A_DAY", 86400);

    $current_day = strtotime($pesanan["tarikh"]);
    $week_start = strtotime(getWeekStart());
    $day = ($current_day - $week_start) / SECONDS_IN_A_DAY;

    if (filter_var($day, FILTER_VALIDATE_FLOAT)) {
        throw new Exception("[day] cannot be float.");
    }

    $array_jumlah_harga_by_day[$day] = $jumlah_harga_belian;
}

echoJsonResponse(true, "RevenueAPI request processed", ["data" => $array_jumlah_harga_by_day, "total" => $jumlah_harga]);

function getWeekStart(): string {
    $day = date("w");
    return date(DATE_FORMAT, strtotime("-" . $day . " days"));
}

function getWeekEnd(): string {
    $day = date("w");
    return date(DATE_FORMAT, strtotime("+" . (6 - $day) . " days"));
}

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
