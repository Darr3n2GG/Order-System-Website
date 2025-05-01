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

    if (isset($get["id_pelanggan"])) {
        $id_pelanggan = htmlspecialchars($get["id_pelanggan"]);
    }

    if (isset($get["range"])) {
        $range = htmlspecialchars($get["range"]);
    }

    if ($range == "*") {
        $array_pesanan = $Pesanan->getSemuaPesanan();
    } else if ($range == "week") {
        $array_pesanan =  $Pesanan->getArrayPesananThisWeek();
    } else if ($range == "date" and isset($get["from"]) and isset($get["to"])) {
        $from = htmlspecialchars($get["from"]);
        $to = htmlspecialchars($get["to"]);
    } else {
        return;
    }

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


    echoJsonResponse(true, "PesananAPI GET request processed.", $array_pesanan);
}

function getHargaFromIDProduk($id): float {
    $Produk = new lib\Produk;
    $produk = $Produk->getProdukFromID($id);
    return $produk["harga"];
}
