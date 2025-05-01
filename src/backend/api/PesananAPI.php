<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/lib/Pesanan.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";

try {
    $Pesanan = new lib\Pesanan;

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET["range"])) {
            $range = htmlspecialchars($_GET["range"]);
            handleGetPesanan($range);
        }
    } else {
        throw new Exception("Invalid PesananAPI request method.", 400);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PesananAPI request failed : " . $e->getMessage());
}

function handleGetPesanan(string $range): void {
    global $Pesanan;

    if ($range == "*") {
        $array_pesanan = $Pesanan->getSemuaPesanan();
        echoJsonResponse(true, "PesananAPI GET request processed.", $array_pesanan);
    } else if ($range == "week") {
        $array_pesanan =  $Pesanan->getArrayPesananThisWeek();
        echoJsonResponse(true, "PesananAPI GET request processed.", $array_pesanan);
    } else if ($range == "date") {
        $from = date(DATE_FORMAT, strtotime($from));
    } else {
        return;
    }
}
