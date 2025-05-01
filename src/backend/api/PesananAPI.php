<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";

try {
    $Pesanan = new lib\Pesanan;
    
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET["filter"])) {
            $filterPelanggan = isset($_GET["pelanggan"]) ? htmlspecialchars($_GET["pelanggan"]) : '';
            // Normal GET request to fetch all pesanan, apply filter if present
            if (!empty($filterPelanggan)) {
                // Fetch filtered Pesanan based on filterPelanggan
                $data = $Pesanan->getPesananByPelanggan($filterPelanggan);
            } else {
                // Fetch all Pesanan if no filter is provided
                $data = $Pesanan->getSemuaPesanan();
            }
            echoJsonResponse(true, "PesananAPI GET request processed.", $data);
    } else {
        handleGetPesanan($_GET);
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
        throw new Exception("Invalid PesananAPI request method.", 400);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PesananAPI request failed : " . $e->getMessage());
}

function handleGetPesanan(array $get): void {
    $Pesanan = new lib\Pesanan;
    $Belian = new lib\Belian;

    $id_pelanggan = isset($get["id_pelanggan"]) ? htmlspecialchars($get["id_pelanggan"]) : null;
    $range = isset($get["range"]) ? htmlspecialchars($get["range"]) : null;


    if ($range == "*") {
        if ($id_pelanggan) {
            $array_pesanan = $Pesanan->getPesananByIDPelanggan($id_pelanggan);
        } else {
            $array_pesanan = $Pesanan->getSemuaPesanan();
        }
    } else if ($range == "week") {
        if ($id_pelanggan) {
            $array_pesanan = $Pesanan->getPesananByIDPelangganWithFilter($id_pelanggan, getWeekStart(), getWeekEnd());
        } else {
            $array_pesanan = $Pesanan->getArrayPesananThisWeek();
        }
    } else if ($range == "date" && isset($get["from"]) && isset($get["to"])) {
        $from = htmlspecialchars($get["from"]);
        $to = htmlspecialchars($get["to"]);

        if ($id_pelanggan) {
            $array_pesanan = $Pesanan->getPesananByIDPelangganWithFilter($id_pelanggan, $from, $to);
        } else {
            $array_pesanan = $Pesanan->getArrayPesananFromRange($from, $to);
        }
    } else {
        $array_pesanan = [];
    }

    if (!count($array_pesanan) == 0) {
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
    }

    echoJsonResponse(true, "PesananAPI GET request processed.", $array_pesanan);
}

function getHargaFromIDProduk($id): float {
    $Produk = new lib\Produk;
    $produk = $Produk->getProdukFromID($id);
    return $produk["harga"];
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
