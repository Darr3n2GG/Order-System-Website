<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/lib/Pesanan.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";

try {
    $Pesanan = new lib\Pesanan;

    if (isset($_GET["range"])) {
        $range = htmlspecialchars($_GET["range"]);
        $array_pesanan = handleGetPesanan($range);

        echoJsonResponse(true, "PesananAPI GET request processed.", $array_pesanan);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PesananAPI request failed : " . $e->getMessage());
}

function handleGetPesanan($range): array | bool {
    global $Pesanan;

    if ($range == "*") {
        return $Pesanan->getSemuaPesanan();
    } else if ($range == "week") {
        return $Pesanan->getArrayPesananThisWeek();
    } else {
        return false;
    }
}
