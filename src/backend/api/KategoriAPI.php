<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";

try {
    $Kategori = new lib\Kategori;
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        handleGetKategoriData();
    } else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        if (isset($_GET["id"])) {
            $id = htmlspecialchars($_GET["id"]);
            $success = $Kategori->deleteKategori($id);

            if ($success) {
                echoJsonResponse(true, "KategoriAPI DELETE request processed.");
            } else {
                echoJsonResponse(false, "Failed to delete kategori.");
            }
        } else {
            throw new Exception("No parameters attached to DELETE request.", 400);
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_GET["type"]) && $_GET["type"] == "insert") {
            handleInsertKategori();
        } else {
            throw new Exception("Invalid POST request type.", 400);
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
        $data = getPatchBody();
        $id = $data["id"];
        unset($data["id"]);

        $Kategori->updateKategori($id, $data);
        echoJsonResponse(true, "KategoriAPI PATCH request processed.");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "KategoriAPI request failed: " . $e->getMessage());
}

function handleGetKategoriData(): void {
    if (isset($_GET["keyword"])) {
        $keyword = htmlspecialchars($_GET["keyword"]);
        returnKategoriFromKeyword($keyword);
    } else {
        returnSemuaKategori();
    }
}

function returnSemuaKategori(): void {
    global $Kategori;

    $array_item_kategori = $Kategori->getSemuaKategori();
    echoJsonResponse(true, "KategoriAPI request processed.", $array_item_kategori);
}

function handleInsertKategori(): void {
    global $Kategori;

    // Get the POST data
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['nama']) || !isset($data['label'])) {
        echoJsonResponse(false, "Missing required fields.", null);
        return;
    }

    // Sanitize and validate the input
    $nama = htmlspecialchars($data['nama']);
    $label = htmlspecialchars($data['label']);

    try {
        // Insert the product into the database
        $insertSuccess = $Kategori->insertKategori($nama, $label);

        if ($insertSuccess) {
            echoJsonResponse(true, "Kategori successfully added.", null);
        } else {
            echoJsonResponse(false, "Failed to insert kategori.", null);
        }
    } catch (Exception $e) {
        echoJsonException($e->getCode(), "Error inserting kategori: " . $e->getMessage());
    }
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
