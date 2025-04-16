<?php

header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";
require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/Database.php";

try {
    $Database = createDatabaseConn();
    $Pelanggan = new lib\Pelanggan;

    $array_pelanggan = getDataSemuaPelanggan();
    echoJsonResponse(true, "PelangganAPI request processed.", $array_pelanggan);
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PelangganAPI request failed : " . $e->getMessage());
}

function getDataSemuaPelanggan(): array {
    global $Pelanggan;
    global $Database;

    $array_pelanggan = $Pelanggan->getSemuaPelanggan();

    foreach ($array_pelanggan as &$pelanggan) {
        $id = $pelanggan["id"];

        $kuantiti_pesanan = $Database->readQuery(
            "SELECT COUNT(id_pelanggan) AS kuantiti_pesanan FROM pesanan WHERE id_pelanggan = ?",
            "i",
            [$id]
        )[0]["kuantiti_pesanan"];

        $pelanggan["kuantiti_pesanan"] = $kuantiti_pesanan;
    }

    return $array_pelanggan;
}
