<?php

header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";
require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/Database.php";

try {
    $Database = createDatabaseConn();
    $Pelanggan = new lib\Pelanggan;

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $array_pelanggan = $Pelanggan->getSemuaSearchablePelanggan();
        echoJsonResponse(true, "PelangganAPI GET request processed.", $array_pelanggan);
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_FILES["files"])) {
            postCSVFiles($_FILES["files"]);
        } else if (arrayCheckKeysExist(["nama", "password", "no_phone"], $_POST)) {
            addPelangganData($_POST["nama"], $_POST["password"], $_POST["no_phone"]);
        } else {
            throw new Exception("No parameters attached to POST request.", 400);
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        if (isset($_GET["id"])) {
            $id = htmlspecialchars($_GET["id"]);
            $Pelanggan->deletePelanggan($id);
            echoJsonResponse(true, "PelangganAPI DELETE request processed.");
        } else {
            throw new Exception("No parameters attached to DELETE request.", 400);
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "PATCH") {
        $body = getPatchBody();
        $id = $body["id"];
        $data = $body["data"];

        $Pelanggan->updatePelanggan($id, $data);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PelangganAPI " . $_SERVER["REQUEST_METHOD"] . " request failed : " . $e->getMessage());
}


function getDataSemuaPelanggan(): array {
    global $Pelanggan;
    global $Database;

    $array_pelanggan = $Pelanggan->getSemuaSearchablePelanggan();

    foreach ($array_pelanggan as &$pelanggan) {
        $id = $pelanggan["id"];

        $kuantiti_pesanan = $Database->readQuery(
            "SELECT COUNT(id_pelanggan) AS kuantiti_pesanan FROM pesanan WHERE id_pelanggan = ?",
            "i",
            [$id]
        )[0]["kuantiti_pesanan"];

        $pelanggan["kuantiti_pesanan"] = $kuantiti_pesanan;
    }

    return $array_pelanggan;
}

function postCSVFiles(array $files): void {
    foreach ($files["name"] as $index => $name) {
        $file = [
            "name" => $name,
            "tmp_name" => $files["tmp_name"][$index],
            "type" => $files["type"][$index],
            "size" => $files["size"][$index],
            "error" => $files["error"][$index]
        ];

        parseCSVFile($file);
    }
    echoJsonResponse(true, "PelangganAPI POST request processed.");
}

function parseCSVFile(array $files): void {
    global $Pelanggan;

    if ($files['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("PelangganAPI POST request failed, file upload error: " . $files["error"] . 422);
    }

    $handle = fopen($files["tmp_name"], "r");
    $header = fgetcsv($handle, 1000);

    if ($handle) {
        while (($data = fgetcsv($handle, 1000)) !== false) {
            $nama = $data[0];
            $password = $data[1];
            $no_phone = $data[2];
            $tahap = $data[3];

            $Pelanggan->addPelanggan($nama, $password, $no_phone);
        }

        fclose($handle);
    }
    echoJsonResponse(true, "PelangganAPI POST request processed.");
}


function addPelangganData(string $nama, string $password, string $no_phone) {
    global $Pelanggan;

    $nama = $_POST["nama"];
    $password = $_POST["password"];
    $no_phone = $_POST["no_phone"];

    $Pelanggan->addPelanggan($nama, $password, $no_phone);
    echoJsonResponse(true, "PelangganAPI POST request processed.");
}

function arrayCheckKeysExist(array $keys, array $array): bool {
    foreach ($keys as $key) {
        if (!array_key_exists($key, $array)) return false;
    }
    return true;
}

function getPatchBody(): array {
    $rawInput = file_get_contents('php://input');
    $body = json_decode($rawInput, true);

    if (!is_array($body)) {
        throw new Exception("Invalid JSON input.");
    }

    return $body;
}
