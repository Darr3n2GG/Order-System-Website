<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/lib/Pesanan.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";

try {
    $Pesanan = new lib\Pesanan;

    if (isset($_GET["range"])) {
        $range = htmlspecialchars($_GET["range"]);

        // Switch case untuk flex haha
        switch ($range) {
            case "*":
                $array_pesanan = $Pesanan->getSemuaPesanan();
                break;
            case "week":
                $array_pesanan = $Pesanan->getArrayPesananThisWeek();
                break;
        }

        echoJsonResponse(true, "PesananAPI GET request processed.", $array_pesanan);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PesananAPI request failed : " . $e->getMessage());
}
