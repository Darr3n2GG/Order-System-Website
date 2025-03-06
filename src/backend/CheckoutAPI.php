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

    $cartAssocArray = json_decode($_POST["cart"], true);

    $MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
    $MySQLConnector->executeQuery(
        "INSERT INTO pesanan (akaun_id, status_id, no_meja, tarikh, cara) VALUES (?, ?, ?, ?, ?)",
        "iiiss",
        [$user_id, 1, $nombor_meja, $tarikh, $cara]
    );

    $id_pesanan = $MySQLConnector->readLastInsertedID();
    $stmt = $MySQLConnector->prepareStatement("INSERT INTO belian (id_pesanan, id_makanan, kuantiti) VALUES (?, ?, ?)");
    foreach ($cartAssocArray as $cartItem) {
        $stmt->bind_param("iii", $id_pesanan, $cartItem["id"], $cartItem["kuantiti"]);
        $stmt->execute();
    }

    echoJsonResponse(true, "CheckoutAPI request processed.");
} catch (Exception $e) {
    echoJsonException("CheckoutAPI request failed : " . $e->getMessage());
}
