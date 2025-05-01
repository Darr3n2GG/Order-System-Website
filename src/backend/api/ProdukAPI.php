<?php
header("Content-Type: application/json");

require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";

/**
 * Parameters :
 *  - type ( data/ html )
 *  - Optional :
 *      - id ( return product of id )
 *      - keyword ( return products which contains keyword )
 *  If optional is not used, it will return all products
 */

try {
    $Produk = new lib\Produk;
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET["type"])) {
            if ($_GET["type"] == "data") {
                handleGetProdukData();
            } else if ($_GET["type"] == "html") {
                handleGetProdukHtml();
            }
        } else {
            throw new Exception("No parameters attached to GET request.", 400);
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_GET["type"]) && $_GET["type"] == "insert") {
            handleInsertProduk();
        } else {
            throw new Exception("Invalid POST request type.", 400);
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echoJsonException($e->getCode(), "ProdukAPI request failed: " . $e->getMessage());
}

function handleGetProdukData(): void {
    if (isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
        returnProdukFromID($id);
    } else if (isset($_GET["keyword"])) {
        $keyword = htmlspecialchars($_GET["keyword"]);
        returnProdukFromKeyword($keyword);
    } else {
        returnSemuaProduk();
    }
}

function handleGetProdukHtml(): void {
    if (isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
        returnProdukHTMLFromId($id);
    } else if (isset($_GET["keyword"])) {
        $keyword = htmlspecialchars($_GET["keyword"]);
        returnProdukHTMLFromKeyword($keyword);
    } else {
        returnSemuaProdukHTML();
    }
}

// New function to handle inserting products
function handleInsertProduk(): void {
    global $Produk;

    // Get the POST data
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['nama']) || !isset($data['kategori']) || !isset($data['harga']) || !isset($data['detail'])) {
        echoJsonResponse(false, "Missing required fields.", null);
        return;
    }

    // Sanitize and validate the input
    $nama = htmlspecialchars($data['nama']);
    $kategori = htmlspecialchars($data['kategori']);
    $harga = (float) $data['harga'];
    $detail = htmlspecialchars($data['detail']);

    try {
        // Insert the product into the database
        $insertSuccess = $Produk->insertProduk($nama, $kategori, $harga, $detail);

        if ($insertSuccess) {
            echoJsonResponse(true, "Product successfully added.", null);
        } else {
            echoJsonResponse(false, "Failed to insert product.", null);
        }
    } catch (Exception $e) {
        echoJsonException($e->getCode(), "Error inserting product: " . $e->getMessage());
    }
}

function returnSemuaProduk(): void {
    global $Produk;

    $array_item_produk = $Produk->getSemuaProduk();
    echoJsonResponse(true, "ProdukAPI request processed.", $array_item_produk);
}

function returnSemuaProdukHTML(): void {
    returnProdukHTMLFromKeyword("");
}

function returnProdukFromID(int $id): void {
    global $Produk;

    $item_produk = $Produk->getProdukFromID($id);
    echoJsonResponse(true, "ProdukAPI request processed.", $item_produk);
}

function returnProdukHTMLFromId(int $id): void {
    global $Produk;

    $produk = $Produk->getProdukFromID($id);
    $html = generateProdukHTML([$produk]);
    echoJsonResponse(true, "ProdukAPI request processed.", $html[0]);
}

function returnProdukFromKeyword(string $keyword): void {
    global $Produk;

    $array_produk = $Produk->getProdukFromKeyword($keyword);
    echoJsonResponse(true, "ProdukAPI request processed.", $array_produk);
}

function returnProdukHTMLFromKeyword(string $keyword): void {
    global $Produk;

    $array_produk = ($keyword == "") ? $Produk->getSemuaProduk() : $Produk->getProdukFromKeyword($keyword);
    $html = generateProdukHTML($array_produk);
    echoJsonResponse(true, "ProdukAPI request processed.", $html);
}

function generateProdukHTML(array $array_produk): array {
    $array_item_produk = [];

    foreach ($array_produk as $produk) {
        $item_produk = lib\MenuLoader::createItemProdukHTML($produk);
        $array_item_produk[] = ["html" => $item_produk, "kategori" => $produk["label"]];
    }

    return $array_item_produk;
}
