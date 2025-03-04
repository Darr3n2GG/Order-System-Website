<?php
class FetchException extends Exception {
    public function errorMessage(): void {
        $msg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
            . ': <b> Fetch request failed, ' . $this->getMessage() . '</b>';
        http_response_code(500);
        echo json_encode(["error" => $msg]);
    }
}

try {
    include_once("account.php");
    $user_id = getUserIDFromSession();
    $nombor_meja = 1;
    $tarikh = date("Y-m-d");
    $cara = "dine-in";

    require_once("MySQLConnector.php");

    $cartAssocArray = json_decode($_POST["cart"]);

    // Create pesanan column with N belians
    $MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
    $MySQLConnector->executeQuery(
        "INSERT INTO pesanan (akaun_id, status_id, no_meja, tarikh, cara) VALUES (?, ?, ?, ?, ?)",
        "iiiss",
        [$user_id, 1, $nombor_meja, $tarikh, $cara]
    );

    // $id_pesanan = $MySQLConnector->readLastInsertedID();
    // $stmt = $MySQLConnector->prepareStatement("INSERT INTO belian (id_pesanan, id_makanan, kuantiti) VALUES (?, ?, ?)");
    // foreach ($cartAssocArray as $cartItem) {
    //     $stmt->bind_param("iii", $id_pesanan, $cartItem["id"], $cartItem["kuantiti"]);
    //     $stmt->execute();
    // }
} catch (Exception $e) {
    throw new FetchException($e->getMessage());
} catch (FetchException $e) {
    $e->errorMessage();
}
