<?php

require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";
require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/Database.php";
require_once dirname(__FILE__) . "/BaseAPIController.php";

class KategoriAPIController extends BaseAPIController {
}

$Database = createDatabaseConn();
$KategoriModel = new lib\Kategori($Database);

$controller = new KategoriAPIController(new class($KategoriModel) {
    private $model;
    public function __construct($model) {
        $this->model = $model;
    }
    public function search($filters) {
        return $this->model->searchKategori($filters);
    }
    public function add($data) {
        return $this->model->addKategori($data);
    }
    public function delete($id) {
        return $this->model->deleteKategori($id);
    }
    public function update($id, $data) {
        return $this->model->updateKategori($id, $data);
    }
});

$controller->handleRequest();
