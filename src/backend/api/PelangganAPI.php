<?php

header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";
require_once dirname(__FILE__, 2) . "/Autoloader.php";

try {
    $Pelanggan = new lib\Pelanggan;

    $array_pelanggan = $Pelanggan->getSemuaPelanggan();
    echoJsonResponse(true, "PelangganAPI request processed.", $array_pelanggan);
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "PelangganAPI request failed : " . $e->getMessage());
}
