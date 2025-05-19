<?php

require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";
require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/Database.php";
require_once dirname(__FILE__) . "/BaseAPIController.php";

class PelangganAPIController extends BaseAPIController {
}

$Database = createDatabaseConn();
$PelangganModel = new lib\Pelanggan($Database);

$controller = new PelangganAPIController(new class($PelangganModel) {
    private $model;
    public function __construct($model) {
        $this->model = $model;
    }
    public function search($filters) {
        return $this->model->searchPelanggan($filters);
    }
    public function add($data) {
        return $this->model->addPelanggan($data);
    }
    public function delete($id) {
        return $this->model->deletePelanggan($id);
    }
    public function update($id, $data) {
        return $this->model->updatePelanggan($id, $data);
    }
});

$controller->handleRequest();
