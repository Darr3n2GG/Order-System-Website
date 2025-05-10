<?php

require_once dirname(__FILE__, 2) . "/JsonResponseHandler.php";
require_once dirname(__FILE__, 2) . "/Autoloader.php";
require_once dirname(__FILE__, 2) . "/Database.php";
require_once dirname(__FILE__) . "/BaseAPIController.php";

class PesananAPIController extends BaseAPIController {
    protected function handleGet(): void {
        $action = $_GET['action'] ?? 'default';

        switch ($action) {
            case 'status':
                $this->handleGetStatus();
                break;
            case 'meja':
                $this->handleGetMeja();
                break;
            default:
                // Use the parent class’s default logic for generic GETs
                parent::handleGet();
                break;
        }
    }
    private function handleGetStatus(): void {
        $statusList = $this->model->getStatus();
        echoJsonResponse(true, "Status list retrieved.", $statusList);
    }
    private function handleGetMeja(): void {
        $mejaList = $this->model->getMeja();
        echoJsonResponse(true, "Meja list retrieved.", $mejaList);
    }
}

$Database = createDatabaseConn();
$PesananModel = new lib\Pesanan2($Database);

$controller = new PesananAPIController(new class($PesananModel) {
    private $model;
    public function __construct($model) {
        $this->model = $model;
    }
    public function search($filters) {
        return $this->model->searchPesanan($filters);
    }
    public function add($data) {
        return $this->model->addPesanan($data);
    }
    public function delete($id) {
        return $this->model->deletePesanan($id);
    }
    public function update($id, $data) {
        return $this->model->updatePesanan($id, $data);
    }
    public function getStatus() {
        return $this->model->getStatus();
    }
    public function getMeja() {
        return $this->model->getMeja();
    }
});

$controller->handleRequest();
