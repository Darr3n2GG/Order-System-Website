<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        handleGetPesanan($_GET);
    } else {
        throw new Exception("Invalid PesananAPI request method.", 400);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PesananAPI request failed : " . $e->getMessage());
}

function handleGetPesanan(array $get): void {
    $Pesanan = new lib\Pesanan;
    $Belian = new lib\Belian;

    $id_pelanggan = isset($get["id_pelanggan"]) ? htmlspecialchars($get["id_pelanggan"]) : null;
    $range = isset($get["range"]) ? htmlspecialchars($get["range"]) : null;


    if ($range == "*") {
        if ($id_pelanggan) {
            $array_pesanan = $Pesanan->getPesananByIDPelanggan($id_pelanggan);
        } else {
            $array_pesanan = $Pesanan->getSemuaPesanan();
        }
    } else if ($range == "week") {
        if ($id_pelanggan) {
            $array_pesanan = $Pesanan->getPesananByIDPelangganWithFilter($id_pelanggan, getWeekStart(), getWeekEnd());
        } else {
            $array_pesanan = $Pesanan->getArrayPesananThisWeek();
        }
    } else if ($range == "date" && isset($get["from"]) && isset($get["to"])) {
        $from = htmlspecialchars($get["from"]);
        $to = htmlspecialchars($get["to"]);

        if ($id_pelanggan) {
            $array_pesanan = $Pesanan->getPesananByIDPelangganWithFilter($id_pelanggan, $from, $to);
        } else {
            $array_pesanan = $Pesanan->getArrayPesananFromRange($from, $to);
        }
    } else {
        $array_pesanan = [];
    }

    if (!count($array_pesanan) == 0) {
        foreach ($array_pesanan as &$pesanan) {
            $jumlah_harga = 0;

            $array_belian = $Belian->getBelianFromIDPesanan($pesanan["id"]);
            foreach ($array_belian as $belian) {
                $harga = getHargaFromIDProduk($belian["id_produk"]);
                $kuantiti = $belian["kuantiti"];

                $jumlah_harga += $harga * $kuantiti;

                $pesanan["jumlah_harga"] = $jumlah_harga;
            }
        }
    }

    echoJsonResponse(true, "PesananAPI GET request processed.", $array_pesanan);
}

function getHargaFromIDProduk($id): float {
    $Produk = new lib\Produk;
    $produk = $Produk->getProdukFromID($id);
    return $produk["harga"];
}
