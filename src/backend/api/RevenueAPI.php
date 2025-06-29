<?php

/**
 * RevenueAPI returns an array of total revenue based on a weekly basis.
 */


header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/Database.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";
require_once dirname(__FILE__, 2) . "/Masa.php";
$Database = createDatabaseConn();

try {

    $Belian = new lib\Belian;
    $Pesanan = new lib\Pesanan($Database);
    $filters["range"] = "week";
    $array_pesanan = $Pesanan->searchPesanan($filters);

    // Array di bawah disusun seperti ini: [ahad, isnin, selasa, rabu, khamis, jumaat, sabtu]
    $jumlah_harga_mingguan = [0, 0, 0, 0, 0, 0, 0];

    $jumlah_harga = 0.0;

    /**
     * Algoritma mendarab harga dan kuantiti produk untuk mendapat jumlah hasil pesanan
     */

    foreach ($array_pesanan as $pesanan) {
        $jumlah_harga_pesanan = 0;
        $array_belian = $Belian->getBelianFromIDPesanan($pesanan["id"]);

        foreach ($array_belian as $belian) {
            $harga = getHargaFromIDProduk($belian["id_produk"]);
            $kuantiti = $belian["kuantiti"];

            $jumlah_harga_pesanan += $harga * $kuantiti;
        }

        // Menyusun hasil mengikuti tarikh pesanan
        $current_day = strtotime($pesanan["tarikh"]);
        $week_start = strtotime(getWeekStart());

        $day = ($current_day - $week_start) / 86400; // saat dalam satu hari
        if (gettype($day) != "integer") {
            throw new Exception("Value of variable (day) must be an integer");
        }

        $jumlah_harga += $jumlah_harga_pesanan;
        $jumlah_harga_mingguan[$day] += $jumlah_harga_pesanan;
    }

    echoJsonResponse(true, "RevenueAPI request processed", ["data" => $jumlah_harga_mingguan, "total" => $jumlah_harga]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "RevenueAPI request failed : " . $e->getMessage());
}

function getHargaFromIDProduk($id): float {
    global $Database;
    $Produk = new lib\Produk($Database);
    $filters['id'] = $id;
    $produk = $Produk->searchProduk($filters);
    return $produk[0]["harga"];
}
