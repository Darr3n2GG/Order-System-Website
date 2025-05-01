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
    } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Handle POST request to create a new pesanan
        $post_condition = isset($_POST["id_pelanggan"], $_POST["tarikh"], $_POST["nombor_meja"], $_POST["cara"]);
        if ($post_condition) {
            handlePostPesanan($_POST["id_pelanggan"], $_POST["tarikh"],  $_POST["nombor_meja"], $_POST["cara"]);
        } else
            if (isset($_GET["type"]) && $_GET["type"] == "insert") {
                handleInsertPesanan();
            } else {
            throw new Exception("Missing parameters in POST request", 400);
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        if (isset($_GET["id"])) {
            $id = htmlspecialchars($_GET["id"]);
            $success = $Pesanan->deletePesanan($id);

            if ($success) {
                echoJsonResponse(true, "PesananAPI DELETE request processed.");
            } else {
                echoJsonResponse(false, "Failed to delete pesanan.");
            }
        } else {
            throw new Exception("No parameters attached to DELETE request.", 400);
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
        $data = getPatchBody();
        $id = $data["id"];
        unset($data["id"]);

        $Pesanan->updatePesanan($id, $data);
        echoJsonResponse(true, "PesananAPI PATCH request processed.");
    }
    else {
        throw new Exception("Invalid request method", 405); // Handle unsupported request methods
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

function handlePostPesanan($id_pelanggan, $tarikh, $nombor_meja, $cara): void {
    global $Pesanan;

    $Pesanan->addPesanan($id_pelanggan, 1, $nombor_meja, $tarikh, $cara);

    echoJsonResponse(true, "PesananAPI POST request processed.");
}

function getPatchBody(): array {
    $rawInput = file_get_contents('php://input');
    $body = json_decode($rawInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON input:" . json_last_error_msg());
    }

    if (!is_array($body)) {
        throw new Exception("Expected JSON object as associative array.");
    }

    return $body;
}

function handleInsertPesanan(): void {
    global $Pesanan;

    // Get the POST data
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['meja']) || !isset($data['tarikh']) || !isset($data['status']) || !isset($data['cara'])) {
        echoJsonResponse(false, "Missing required fields.", null);
        return;
    }

    // Sanitize and validate the input
    $id_pelanggan = (int) $data['idPelanggan'];
//    $id_pelanggan = 1;
    $id_status = 1; // assuming default status
    $no_meja = (int) $data['meja'];
    $tarikh = htmlspecialchars(trim($data['tarikh']));
    $cara = htmlspecialchars(trim($data['cara']));
    
    try {
        // Insert the product into the database
        $Pesanan->addPesanan( $id_pelanggan, $id_status, $no_meja, $tarikh, $cara);
        echoJsonResponse(true, "Pesanan successfully added.", null);
    } catch (Exception $e) {
        echoJsonException($e->getCode(), "Error inserting pesanan: " . $e->getMessage());
    }
}
