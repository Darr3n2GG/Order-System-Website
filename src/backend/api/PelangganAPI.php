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
        handlePostPesanan();
    } else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        $id = htmlspecialchars($_GET["id"]);
        $Pelanggan->deletePelanggan($id);

        echoJsonResponse(true, "PelangganAPI DELETE request processed.");
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

function handlePostPesanan(): void {
    if (isset($_FILES["files"])) {
        foreach ($_FILES["files"]["name"] as $index => $name) {
            $file = [
                "name" => $name,
                "tmp_name" => $_FILES["files"]["tmp_name"][$index],
                "type" => $_FILES["files"]["type"][$index],
                "size" => $_FILES["files"]["size"][$index],
                "error" => $_FILES["files"]["error"][$index]
            ];

            parseCSVFile($file);
        }
    } else if (isset($_POST)) {
        global $Pelanggan;

        $nama = $_POST["nama"];
        $password = $_POST["password"];
        $no_phone = $_POST["no_phone"];

        $Pelanggan->addPelanggan($nama, $password, $no_phone);
        echoJsonResponse(true, "PelangganAPI POST request processed.");
    } else {
        throw new Exception("No parameters attached to POST request.", 400);
    }
}

function parseCSVFile(array $files): void {
    global $Pelanggan;

    if ($files['error'] !== UPLOAD_ERR_OK) {
        echoJsonException(422, "PelangganAPI POST request failed, file upload error: " . $files["error"]);
        return;
    }

    $handle = fopen($files["tmp_name"], "r");
    $header = fgetcsv($handle, 1000);

    if ($handle) {
        while (($data = fgetcsv($handle, 1000)) !== false) {
            $nama = $data[0];
            $password = $data[1];
            $no_phone = $data[2];

            $Pelanggan->addPelanggan($nama, $password, $no_phone);
        }

        fclose($handle);
    }
    echoJsonResponse(true, "PelangganAPI POST request processed.");
}
