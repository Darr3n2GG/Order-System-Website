<?php
class MySQLConnector {
    private $conn;

    public function __construct(
        string $hostname,
        string $username,
        string $password,
        string $database,
    ) {
        try {
            $this->conn = mysqli_connect($hostname, $username, $password, $database);
            if ($this->conn->connect_error) {
                throw new Exception("Failed to connect to MySQL: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function __destruct() {
        try {
            $this->conn->close();
        } catch (Exception $e) {
            exit("Unable to close connection: " . $e->getMessage());
        }
    }

    public function readQuery(string $query, string $types = "", array $array_of_params = null): array {
        try {
            $stmt = $this->prepareStatement($query);
            if ($types != "" and $array_of_params != null) {
                $stmt->bind_param($types, ...$array_of_params);
            } elseif ($types != "" or $array_of_params != null) {
                throw new Exception("Both the types and array of params parameters must contain a value");
            }
            $result = $this->readStatement($stmt);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    public function executeQuery(string $query, string $types, array $array_of_params): void {
        $stmt = $this->prepareStatement($query);
        $stmt->bind_param($types, ...$array_of_params);
        $stmt->execute();
        $stmt->close();
    }

    public function readLastInsertedID(): int {
        $stmt = $this->prepareStatement("SELECT LAST_INSERT_ID()");
        $result = $this->readStatement($stmt);
        return $result[0]["LAST_INSERT_ID()"];
    }

    public function prepareStatement(string $query): mysqli_stmt {
        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt == false) {
                throw new Exception("Unable to prepare query: " . $query);
            }
            return $stmt;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    public function readStatement(mysqli_stmt $stmt): array {
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }
}
