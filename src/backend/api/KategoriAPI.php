<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";
require_once dirname(__FILE__, 2) . "/Database.php";

try {
    $Database = createDatabaseConn();

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $array_kategori = $Database->readQuery("SELECT id, label, nama from kategori");
        echoJsonResponse(true, "KategoriAPI request processed.", $array_kategori);
    } else {
        throw new Exception("Invalid KategoriAPI request method", 400);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "ProdukAPI request failed : " . $e->getMessage());
}
