<?php
abstract class BaseAPIController {
    protected $model;
    protected $requestMethod;

    public function __construct($model) {
        $this->model = $model;
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
    }

    public function handleRequest(): void {
        try {
            switch ($this->requestMethod) {
                case "GET":
                    $this->handleGet();
                    break;
                case "POST":
                    $this->handlePost();
                    break;
                case "DELETE":
                    $this->handleDelete();
                    break;
                case "PATCH":
                    $this->handlePatch();
                    break;
                default:
                    throw new Exception("Invalid request method", 400);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            echoJsonException(
                $e->getCode() ?: 500,
                get_class($this) . " {$this->requestMethod} request failed: " . $e->getMessage()
            );
        }
    }

    protected function handleGet(): void {
        $filters = array_filter($_GET, fn($v) => trim($v) !== '');
        $results = $this->model->search($filters);
        echoJsonResponse(true, "GET request processed.", $results);
    }

    protected function handlePost(): void {
        if (isset($_FILES["files"])) {
            $this->handleCSVUpload($_FILES["files"]);
        } elseif (!empty($_POST)) {
            $this->model->add($_POST);
            echoJsonResponse(true, "Data added successfully.");
        } else {
            throw new Exception("No valid POST data found!", 400);
        }
    }

    protected function handleDelete(): void {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id || $id <= 0) {
            throw new Exception("Invalid or missing 'id' parameter.", 400);
        }

        $this->model->delete($id);
        echoJsonResponse(true, "Deleted successfully.");
    }

    protected function handlePatch(): void {
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        if ($data === null || !isset($data["id"])) {
            throw new Exception("Invalid JSON or missing 'id'.", 400);
        }

        $id = filter_var($data["id"], FILTER_VALIDATE_INT);
        if (!$id || $id <= 0) {
            throw new Exception("Invalid 'id'.", 400);
        }

        unset($data["id"]);
        if (empty($data)) {
            throw new Exception("No data provided for update.", 400);
        }

        $this->model->update($id, $data);
        echoJsonResponse(true, "Updated successfully.");
    }

    protected function handleCSVUpload(array $files): void {
        foreach ($files["name"] as $index => $name) {
            $file = [
                "name" => $name,
                "tmp_name" => $files["tmp_name"][$index],
                "type" => $files["type"][$index],
                "size" => $files["size"][$index],
                "error" => $files["error"][$index]
            ];
            $this->parseCSV($file);
        }
        echoJsonResponse(true, "CSV processed successfully.");
    }

    protected function parseCSV(array $file): void {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("CSV upload error: " . $file["error"], 422);
        }

        $handle = fopen($file["tmp_name"], "r");
        if (!$handle) {
            throw new Exception("Unable to open CSV file.", 422);
        }

        $header = fgetcsv($handle, 1000);
        if (!$header) {
            throw new Exception("CSV header is missing or invalid.", 422);
        }

        while (($row = fgetcsv($handle, 1000)) !== false) {
            $rowData = array_combine($header, $row);
            if ($rowData === false) continue;
            $this->model->add($rowData);
        }

        fclose($handle);
    }
}
