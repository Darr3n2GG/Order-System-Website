<?php
require_once("Makanan.php");
require_once("JsonResponseHandler.php");

try {
    $makanan_id = isset($_GET["id"]) ? $_GET["id"] : 0;
    $objek_makanan = new Makanan;
    $makanan = $objek_makanan->getMakanan($makanan_id);
    echoJsonResponse(true, "MakananAPI request processed.", ["item" => $makanan[0]]);
} catch (Exception $e) {
    echoJsonException("MakananAPI request failed : " . $e->getMessage());
}
