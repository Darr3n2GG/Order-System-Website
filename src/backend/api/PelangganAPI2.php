<?php

header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";
require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/Database.php";

try {
    $Database = createDatabaseConn();
    $Pelanggan = new lib\Pelanggan2($Database);

    $method = $_SERVER["REQUEST_METHOD"];

    switch ($method) {
        case "GET":
            handleGetRequest($Pelanggan);
            break;

        case "POST":
            handlePostRequest($Pelanggan);
            break;

        case "DELETE":
            handleDeleteRequest($Pelanggan);
            break;

        case "PATCH":
            handlePatchRequest($Pelanggan);
            break;

        default:
            throw new Exception("Invalid PelangganAPI request method", 400);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException(
        $e->getCode() ?: 500,
        "PelangganAPI {$method} request failed: " . $e->getMessage()
    );
}

// --- GET ---
function handleGetRequest($Pelanggan): void {
    $filters = [];

    foreach ($_GET as $key => $value) {
        if (trim($value) !== '') {
            $filters[$key] = $value;
        }
    }

    $pelangganList = $Pelanggan->searchPelanggan($filters);

    echoJsonResponse(true, "PelangganAPI GET request processed.", $pelangganList);
}


// --- POST ---
function handlePostRequest($Pelanggan): void {
    try {
        if (isset($_FILES["files"])) {
            // Process uploaded CSV file
            postCSVFiles($_FILES["files"], $Pelanggan);
        } elseif (!empty($_POST)) {
            // Flexibly add pelanggan using all POST fields
            $Pelanggan->addPelanggan($_POST);
            echoJsonResponse(true, "Pelanggan added successfully.");
        } else {
            throw new Exception("No valid POST data found!", 400);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echoJsonException($e->getCode(), "Failed to handle POST request: " . $e->getMessage());
    }
}

// --- DELETE ---
function handleDeleteRequest($Pelanggan): void {
    if (!isset($_GET["id"])) {
        throw new Exception("Missing 'id' parameter for DELETE.", 400);
    }

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id <= 0) {
        throw new Exception("Invalid 'id' parameter. Must be a positive integer.", 400);
    }

    $Pelanggan->deletePelanggan($id);
    echoJsonResponse(true, "Pelanggan deleted successfully.");
}

// --- PATCH ---
function handlePatchRequest($Pelanggan): void {
    // Read the raw POST data (JSON)
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true); // Decode JSON to associative array

    // Check if JSON decoding was successful
    if ($data === null) {
        throw new Exception("Invalid JSON provided.", 400);
    }

    // Ensure 'id' is provided in the request
    if (!isset($data["id"])) {
        throw new Exception("PATCH request must include 'id'.", 400);
    }

    // Validate 'id'
    $id = filter_var($data["id"], FILTER_VALIDATE_INT);
    if ($id === false || $id === null || $id <= 0) {
        throw new Exception("Invalid 'id' in PATCH request. Must be a positive integer.", 400);
    }

    // Remove the 'id' from the data array as it's not needed for update
    unset($data["id"]);

    // Check if there is any other data to update
    if (empty($data)) {
        throw new Exception("No data provided for update.", 400);
    }

    // Call the method to update the customer
    $Pelanggan->updatePelanggan($id, $data);

    // Send a success response
    echoJsonResponse(true, "Pelanggan updated successfully.");
}

// CSV
function postCSVFiles(array $files, $Pelanggan): void {
    foreach ($files["name"] as $index => $name) {
        $file = [
            "name" => $name,
            "tmp_name" => $files["tmp_name"][$index],
            "type" => $files["type"][$index],
            "size" => $files["size"][$index],
            "error" => $files["error"][$index]
        ];
        parseCSVFile($file, $Pelanggan);
    }
}

function parseCSVFile(array $files, $Pelanggan): void {
    if ($files['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("PelangganAPI POST request failed, file upload error: " . $files["error"], 422);
    }

    $handle = fopen($files["tmp_name"], "r");
    if (!$handle) {
        throw new Exception("Unable to open uploaded CSV file.", 422);
    }

    $header = fgetcsv($handle, 1000); // read the header row
    if (!$header) {
        throw new Exception("CSV header is missing or invalid.", 422);
    }

    while (($row = fgetcsv($handle, 1000)) !== false) {
        $rowData = array_combine($header, $row);
        if ($rowData === false) {
            continue; // skip malformed rows
        }

        $Pelanggan->addPelanggan($rowData);
    }

    fclose($handle);
    echoJsonResponse(true, "PelangganAPI POST request processed.");
}


// --- Utilities ---
function validatePostParams(array $requiredKeys): bool {
    foreach ($requiredKeys as $key) {
        if (!isset($_POST[$key])) return false;
    }
    return true;
}

// function getJsonBody(): array {
//     $raw = file_get_contents('php://input');
//     $decoded = json_decode($raw, true);

//     if (json_last_error() !== JSON_ERROR_NONE) {
//         throw new Exception("Invalid JSON: " . json_last_error_msg(), 400);
//     }

//     return $decoded;
// }
