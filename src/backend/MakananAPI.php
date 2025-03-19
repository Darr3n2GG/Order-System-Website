<?php
header("Content-Type: application/json");

require_once(__DIR__ . "/Makanan.php");
require_once(__DIR__ . "/JsonResponseHandler.php");
require_once(__DIR__ . "/ErrorHandler.php");
require_once(__DIR__ . "/MenuLoader.php");


try {
    if (isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
        returnMakananFromID($id);
    } elseif (isset($_GET["keyword"])) {
        $keyword = htmlspecialchars($_GET["keyword"]);
        returnMakananFromKeyword($keyword);
    } else {
        throw new Exception("No parameters attached to GET request.", 400);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "MakananAPI request failed : " . $e->getMessage());
}


function returnMakananFromID(int $id): void {
    $Makanan = new Makanan;
    $item_makanan = $Makanan->getMakananFromID($id);
    echoJsonResponse(true, "MakananAPI request processed.", ["item" => $item_makanan]);
}

function returnMakananFromKeyword(string $keyword): void {
    $Makanan = new Makanan;
    if ($keyword == "") {
        $array_makanan = $Makanan->getAllMakanan();
        $response = generateMakananHTML($array_makanan);
    } else {
        $array_makanan = $Makanan->getMakananFromKeyword($keyword);
        $response = generateMakananHTML($array_makanan);
    }
    echoJsonResponse(true, "MakananAPI request processed.", ["items" => $response]);
}

function generateMakananHTML(array $array_makanan): array {
    $array_makanan_item = [];

    foreach ($array_makanan as $makanan) {
        $makanan_item = MenuLoader::createMakananItem($makanan);
        array_push($array_makanan_item, ["html" => $makanan_item, "kategori" => $makanan["label"]]);
    }

    return $array_makanan_item;
}
