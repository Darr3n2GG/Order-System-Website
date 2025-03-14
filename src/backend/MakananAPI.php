<?php
header("Content-Type: application/json");

require_once("Makanan.php");
require_once("JsonResponseHandler.php");

setJsonExceptionHandler();

try {
    $id = isset($_GET["id"]) ? $_GET["id"] : null;
    $keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : null;
    $Makanan = new Makanan;

    if ($id) {
        returnMakananFromID($id);
    } elseif (isset($keyword)) {
        returnMakananFromKeyword($keyword);
    } else {
        throw new Exception("No parameters attached to GET request.");
    }
} catch (Exception $e) {
    throw new Exception("MakananAPI request failed : " . $e->getMessage());
}

function returnMakananFromID(int $id): void {
    global $Makanan;
    $item_makanan = $Makanan->getMakananFromID($id);
    echoJsonResponse(true, "MakananAPI request processed.", ["item" => $item_makanan]);
}

function returnMakananFromKeyword(string $keyword): void {
    global $Makanan;
    if ($keyword == "") {
        $array_makanan = $Makanan->getAllMakanan();
        $makananHTML = generateMakananHTML($array_makanan);
    } else {
        $array_makanan = $Makanan->getMakananFromKeyword($keyword);
        $makananHTML = generateMakananHTML($array_makanan);
    }
    echoJsonResponse(true, "MakananAPI request processed.", ["items" => $makananHTML]);
}

function generateMakananHTML(array $array_makanan): array {
    $array_makanan_item = [];

    foreach ($array_makanan as $makanan) {
        $gambar = $makanan["gambar"];
        $nama = $makanan["nama"];
        $id = $makanan["id"];
        $label = $makanan["label"] . $id;
        $harga = $makanan["harga"];
        $kategori = $makanan["kategori"];
        $makanan_item = <<<ITEM
            <div class='food_item' data-id='$id' data-category='$kategori'>
                <img src='$gambar' alt='$nama'>
                <div class='food_info'>
                    <div class='food_row'>
                        <h2>$nama</h2>
                        <sl-tag size='small' pill>$label</sl-tag>
                    </div>
                    <div class='food_row'>
                        <p><strong>Harga : RM $harga</strong></p>
                    </div>
                </div>
            </div>
        ITEM;

        array_push($array_makanan_item, $makanan_item);
    }

    return $array_makanan_item;
}
