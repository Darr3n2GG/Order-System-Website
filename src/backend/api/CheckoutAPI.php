<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/Database.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";
require_once dirname(__FILE__, 2) . "/Autoloader.php";

try {
    if (!isset($_POST["cart"])) {
        throw new Exception("No cart body in API request", 400);
    }

    $Session = new lib\Session;
    $Database = createDatabaseConn();

    $id_pelanggan = $Session->getIDPelanggan();
    $nombor_meja = 1;
    $tarikh = date(DATE_FORMAT);
    $cara = "dine-in";

    $cart_assoc_array = json_decode($_POST["cart"], true);

    $Pesanan = new lib\Pesanan;
    $Pesanan->addPesanan($id_pelanggan, 1, $nombor_meja, $tarikh, $cara);

    $Belian = new lib\Belian;
    $id_pesanan = $Pesanan->getLastInsertedIDOfPesanan();
    $Belian->addBelian($id_pesanan, $cart_assoc_array);

    echoJsonResponse(true, "CheckoutAPI request processed.");
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "CheckoutAPI request failed : " . $e->getMessage());
}

function addBelian(int $id_pesanan, array $cart_assoc_array): void {
    global $Database;
    $stmt = $Database->prepareStatement("INSERT INTO belian (id_pesanan, id_produk, kuantiti) VALUES (?, ?, ?)");
    foreach ($cart_assoc_array as $cart_item) {
        $stmt->bind_param("iii", $id_pesanan, $cart_item["id"], $cart_item["kuantiti"]);
        $stmt->execute();
    }
    $stmt->close();
}
