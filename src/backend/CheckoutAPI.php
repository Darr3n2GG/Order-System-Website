<?php
require_once("Session.php");
require_once("JsonResponseHandler.php");
require_once("MySQLConnector.php");

setJsonExceptionHandler();

try {
    $user_id = getUserIDFromSession();
    $nombor_meja = 1;
    $tarikh = date("Y-m-d");
    $cara = "dine-in";

    $cart_assoc_array = json_decode($_POST["cart"], true);
    $MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");

    addPesanan($user_id, 1, $nombor_meja, $tarikh, $cara);

    $id_pesanan = $MySQLConnector->readLastInsertedID();
    addBelian($id_pesanan, $cart_assoc_array);

    echoJsonResponse(true, "CheckoutAPI request processed.");
} catch (Exception $e) {
    echoJsonException("CheckoutAPI request failed at line" . $e->getLine() . " : " . $e->getMessage());
}

function addPesanan(int $user_id, int $status_id, int $nombor_meja, string $tarikh, string $cara): void {
    global $MySQLConnector;
    $MySQLConnector->executeQuery(
        "INSERT INTO pesanan (akaun_id, status_id, no_meja, tarikh, cara) VALUES (?, ?, ?, ?, ?)",
        "iiiss",
        [$user_id, $status_id, $nombor_meja, $tarikh, $cara]
    );
}

function addBelian(int $id_pesanan, array $cart_assoc_array): void {
    global $MySQLConnector;
    $stmt = $MySQLConnector->prepareStatement("INSERT INTO belian (id_pesanan, id_makanan, kuantiti) VALUES (?, ?, ?)");
    foreach ($cart_assoc_array as $cart_item) {
        $stmt->bind_param("iii", $id_pesanan, $cart_item["id"], $cart_item["kuantiti"]);
        $stmt->execute();
    }
}
