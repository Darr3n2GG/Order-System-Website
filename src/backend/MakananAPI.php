<?php
require_once("Makanan.php");
require_once("JsonResponseHandler.php");

try {
    if (isset($_GET["id"])) {
        $makanan_id = $_GET["id"];
        $Makanan = new Makanan;
        $item_makanan = $Makanan->getMakanan($makanan_id);
        echoJsonResponse(true, "MakananAPI request processed.", ["item" => $item_makanan[0]]);
    } else if (isset($_GET["keyword"])) {
        return;
    } else {
        throw new Exception("No parameters attached to GET request.");
    }
} catch (Exception $e) {
    echoJsonException("MakananAPI request failed : " . $e->getMessage());
}
