<?php
header("Content-Type: application/json");

require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/JsonResponseHandler.php");
require_once(__DIR__ . "/ErrorHandler.php");


try {
    $Database = createDatabaseConn();
    $array_pesanan = $Database->readQuery(
        "SELECT pesanan.id as id, pelanggan.nama as nama, pesanan.tarikh as tarikh,
        status.status as status, pesanan.cara as cara, pesanan.no_meja as no_meja
        FROM pesanan
        INNER JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id
        INNER JOIN status ON pesanan.id_status = status.id"
    );

    echoJsonResponse(true, "PesananAPI request processed.", $array_pesanan);
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PesananAPI request failed : " . $e->getMessage());
}
