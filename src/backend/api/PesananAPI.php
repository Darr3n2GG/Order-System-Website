<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/lib/Pesanan.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";

try {
    $Pesanan = new lib\Pesanan;

    if (isset($_GET["range"])) {
        $range = htmlspecialchars($_GET["range"]);
        if ($range == "*") {
            $array_pesanan = $Pesanan->getSemuaPesanan();

            echoJsonResponse(true, "PesananAPI request processed.", $array_pesanan);
        } elseif ($range = "week") {
            $array_pesanan = $Pesanan->getArrayPesananThisWeek();

            echoJsonResponse(true, "PesananAPI request processed.", $array_pesanan);
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PesananAPI request failed : " . $e->getMessage());
}
