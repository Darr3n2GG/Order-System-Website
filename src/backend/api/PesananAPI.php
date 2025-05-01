<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/lib/Pesanan.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";

try {
    $Pesanan = new lib\Pesanan;

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Check if there's a filter for pelanggan (customer)
        $filterPelanggan = isset($_GET["pelanggan"]) ? htmlspecialchars($_GET["pelanggan"]) : '';

        if (isset($_GET["range"])) {
            // Handle GET request with range parameter (e.g., "*" or "week")
            $range = htmlspecialchars($_GET["range"]);
            $data = handleGetPesanan($range);
            echoJsonResponse(true, "PesananAPI GET request processed.", $data);
        } else {
            // Normal GET request to fetch all pesanan, apply filter if present
            if (!empty($filterPelanggan)) {
                // Fetch filtered Pesanan based on filterPelanggan
                $data = $Pesanan->getPesananByPelanggan($filterPelanggan);
            } else {
                // Fetch all Pesanan if no filter is provided
                $data = $Pesanan->getSemuaPesanan();
            }
            echoJsonResponse(true, "PesananAPI GET request processed.", $data);
        }
    } else {
        throw new Exception("Invalid request method", 405); // Handle unsupported request methods
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Handle POST request to create a new pesanan
        if (isset($_POST["id_pelanggan"], $_POST["tarikh"], $_POST["pesanan_detail"])) {
            handlePostPesanan($_POST["id_pelanggan"], $_POST["tarikh"], $_POST["pesanan_detail"]);
        } else {
            throw new Exception("Missing parameters in POST request", 400);
        }
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PesananAPI request failed: " . $e->getMessage());
}

function handleGetPesanan($range): array {
    global $Pesanan;

    if ($range == "*") {
        return $Pesanan->getSemuaPesanan();
    } else if ($range == "week") {
        return $Pesanan->getArrayPesananThisWeek();
    } else {
        throw new Exception("Invalid range parameter value", 400);
    }
}

function handlePostPesanan($id_pelanggan, $tarikh, $pesanan_detail): void {
    global $Pesanan;

    // Assuming addPesanan takes these three parameters
    $Pesanan->addPesanan($id_pelanggan, $tarikh, $pesanan_detail);

    echoJsonResponse(true, "PesananAPI POST request processed.");
}
