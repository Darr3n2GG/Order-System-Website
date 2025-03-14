<?php
class MySQLConnector {
    private $conn;

    public function __construct(
        string $hostname,
        string $username,
        string $password,
        string $database,
    ) {
        $this->conn = mysqli_connect($hostname, $username, $password, $database);
        if ($this->conn->connect_error) {
            throw new Exception("Failed to connect to MySQL: " . $this->conn->connect_error);
        }
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function readQuery(string $query, string $types = "", array|null $array_of_params = null): array {
        $stmt = $this->prepareStatement($query);
        if ($types != "" and $array_of_params != null) {
            $stmt->bind_param($types, ...$array_of_params);
        } elseif ($types != "" or $array_of_params != null) {
            throw new Exception("Both the types and array of params parameters must contain a value");
        }
        $result = $this->readStatement($stmt);
        $stmt->close();
        return $result;
    }

    public function executeQuery(string $query, string $types, array $array_of_params): void {
        $stmt = $this->prepareStatement($query);
        $stmt->bind_param($types, ...$array_of_params);
        $stmt->execute();
        $stmt->close();
    }

    public function readLastInsertedID(): int {
        $last_inserted_id = $this->conn->insert_id;
        if ($last_inserted_id == 0) {
            throw new Exception("No previous query on the connection or query did not update an AUTO_INCREMENT value");
        }
        return $last_inserted_id;
    }

    public function prepareStatement(string $query): mysqli_stmt {
        $stmt = $this->conn->prepare($query);
        if ($stmt == false) {
            throw new Exception("Unable to prepare query: " . $query);
        }
        return $stmt;
    }

    public function readStatement(mysqli_stmt $stmt): array {
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return $result;
    }
}
