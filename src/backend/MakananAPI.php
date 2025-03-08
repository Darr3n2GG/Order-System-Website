<?php
require_once("Makanan.php");
require_once("JsonResponseHandler.php");

try {
    $makanan_id = isset($_GET["id"]) ? $_GET["id"] : 0;
    $Makanan = new Makanan;
    $item_makanan = $Makanan->getMakanan($makanan_id);
    echoJsonResponse(true, "MakananAPI request processed.", ["item" => $item_makanan[0]]);
} catch (Exception $e) {
    echoJsonException("MakananAPI request failed : " . $e->getMessage());
}
