<?php
include_once("JsonExceptionHandler.php");
include_once("account.php");

try {
    $user_id = getUserIDFromSession();
    $nombor_meja = 1;
    $tarikh = date("Y-m-d");
    $cara = "dine-in";

    require_once("MySQLConnector.php");

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

    echo json_encode(["ok" => true, "message" => "Fetch request processed."]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => "Fetch request failed : " . $e->getMessage()]);
}
