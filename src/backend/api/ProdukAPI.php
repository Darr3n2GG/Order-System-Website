<?php

require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";
require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/Database.php";
require_once dirname(__FILE__) . "/BaseAPIController.php";
require_once dirname(__FILE__, 3) . "/scripts/MenuLoader.php";

class ProdukAPIController extends BaseAPIController {
    protected function handleGet(): void {
        $action = $_GET['type'] ?? 'default';
        unset($_GET['type']);
        switch ($action) {
            case 'html':
                $filters = $_GET;

                $produkList = $this->model->search($filters);

                $htmlResult = $this->generateProdukHTML($produkList);
                echoJsonResponse(true, "HTML generated successfully.", $htmlResult);
                break;

            default:
                parent::handleGet();
                break;
        }
    }
    protected function handlePost(): void {
        $requestUri = $_SERVER["REQUEST_URI"];
        if (str_contains($requestUri, "/upload-image")) {
            $this->handleImageUpload();
        } else {
            parent::handlePost();
        }
    }
    // Method to handle image uploads
    private function handleImageUpload(): void {
        if (empty($_FILES["image"])) {
            throw new Exception("No image file uploaded.", 400);
        }
        $imagePath = $this->model->uploadImage($_FILES["image"]);
        echoJsonResponse(true, "Image uploaded successfully.", ["imagePath" => $imagePath]);
    }

    function generateProdukHTML(array $array_produk): array {
        $array_item_produk = [];

        foreach ($array_produk as $produk) {
            $item_produk = MenuLoader::createItemProdukHTML($produk);
            $array_item_produk[] = ["html" => $item_produk, "kategori" => $produk["label"]];
        }

        return $array_item_produk;
    }
}

$Database = createDatabaseConn();
$ProdukModel = new lib\Produk($Database);

$controller = new ProdukAPIController(new class($ProdukModel) {
    private $model;
    public function __construct($model) {
        $this->model = $model;
    }
    public function search($filters) {
        return $this->model->searchProduk($filters);
    }
    public function add($data) {
        return $this->model->addProduk($data);
    }
    public function delete($id) {
        return $this->model->deleteProduk($id);
    }
    public function update($id, $data) {
        return $this->model->updateProduk($id, $data);
    }
    public function getKategori() {
        return $this->model->getKategori();
    }
    // expose public function of Produk Model
    public function uploadImage($file) {
        return $this->model->uploadImage($file);
    }
});

$controller->handleRequest();
