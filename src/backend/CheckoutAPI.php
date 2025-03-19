<?php
header("Content-Type: application/json");

require_once(__DIR__ . "/Session.php");
require_once(__DIR__ . "/JsonResponseHandler.php");
require_once(__DIR__ . "/ErrorHandler.php");
require_once(__DIR__ . "/Database.php");

try {
    if (!isset($_POST["cart"])) {
        throw new Exception("No cart body in API request", 400);
    }

    $Database = DatabaseFactory();

    $user_id = getUserIDFromSession();
    $nombor_meja = 1;
    $tarikh = date("Y-m-d");
    $cara = "dine-in";

    $cart_assoc_array = json_decode($_POST["cart"], true);

    addPesanan($user_id, 1, $nombor_meja, $tarikh, $cara);

    $id_pesanan = $Database->readLastInsertedID();
    addBelian($id_pesanan, $cart_assoc_array);

    echoJsonResponse(true, "CheckoutAPI request processed.");
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "CheckoutAPI request failed : " . $e->getMessage());
}

function addPesanan(int $user_id, int $status_id, int $nombor_meja, string $tarikh, string $cara): void {
    global $Database;
    $Database->executeQuery(
        "INSERT INTO pesanan (akaun_id, status_id, no_meja, tarikh, cara) VALUES (?, ?, ?, ?, ?)",
        "iiiss",
        [$user_id, $status_id, $nombor_meja, $tarikh, $cara]
    );
}

function addBelian(int $id_pesanan, array $cart_assoc_array): void {
    global $Database;
    $stmt = $Database->prepareStatement("INSERT INTO belian (id_pesanan, id_makanan, kuantiti) VALUES (?, ?, ?)");
    foreach ($cart_assoc_array as $cart_item) {
        $stmt->bind_param("iii", $id_pesanan, $cart_item["id"], $cart_item["kuantiti"]);
        $stmt->execute();
    }
}
