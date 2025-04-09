<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 3) . "/vendor/autoload.php";

try {
    $Produk = new lib\Produk;

    if (isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
        if ($id == "*") {
            returnSemuaProduk();
        } else {
            returnProdukFromID($id);
        }
    } elseif (isset($_GET["keyword"])) {
        $keyword = htmlspecialchars($_GET["keyword"]);
        returnProdukHTMLFromKeyword($keyword);
    } else {
        throw new Exception("No parameters attached to GET request.", 400);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "ProdukAPI request failed : " . $e->getMessage());
}

function returnSemuaProduk(): void {
    global $Produk;

    $array_item_produk = $Produk->getSemuaProduk();
    echoJsonResponse(true, "ProdukAPI request processed.", ["item" => $array_item_produk]);
}

function returnProdukFromID(int $id): void {
    global $Produk;

    $item_produk = $Produk->getProdukFromID($id);
    echoJsonResponse(true, "ProdukAPI request processed.", ["item" => $item_produk]);
}

function returnProdukHTMLFromKeyword(string $keyword): void {
    global $Produk;

    $array_produk = ($keyword == "") ? $Produk->getSemuaProduk() : $Produk->getProdukFromKeyword($keyword);
    $response = generateProdukHTML($array_produk);
    echoJsonResponse(true, "ProdukAPI request processed.", ["items" => $response]);
}

function generateProdukHTML(array $array_produk): array {
    $array_item_produk = [];

    foreach ($array_produk as $produk) {
        $item_produk = lib\MenuLoader::createItemProdukHTML($produk);
        array_push($array_item_produk, ["html" => $item_produk, "kategori" => $produk["label"]]);
    }

    return $array_item_produk;
}
