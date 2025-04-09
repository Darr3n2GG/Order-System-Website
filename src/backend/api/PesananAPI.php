<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/lib/Pesanan.php";
require_once dirname(__FILE__, 2) . "/Database.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";

try {
    $Database = createDatabaseConn();
    $Pesanan = new Pesanan;
    $array_pesanan = $Pesanan->getSemuaPesanan();

    echoJsonResponse(true, "PesananAPI request processed.", $array_pesanan);
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PesananAPI request failed : " . $e->getMessage());
}
