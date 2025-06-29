<?php

namespace lib;

class MySQLConnector {
    private $conn;

    public function __construct(
        string $hostname,
        string $username,
        string $password,
        string $database,
    ) {
        $this->conn = new \mysqli($hostname, $username, $password, $database);
        if ($this->conn->connect_error) {
            throw new MySQLConnectorException("Failed to connect to MySQL: " . $this->conn->connect_error);
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
            throw new MySQLConnectorException("Both the { types } and { array_of_params } parameters must contain a value.");
        }
        $result = $this->readStatement($stmt);
        $stmt->close();
        return $result;
    }

    public function executeQuery(string $query, string $types, array $array_of_params): bool {
        $stmt = $this->prepareStatement($query);
        $stmt->bind_param($types, ...$array_of_params);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function readLastInsertedID(): int {
        $last_inserted_id = $this->conn->insert_id;
        if ($last_inserted_id == 0) {
            throw new MySQLConnectorException("No previous query on the connection or query updated an AUTO_INCREMENT value.");
        }
        return $last_inserted_id;
    }

    public function prepareStatement(string $query): \mysqli_stmt {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new MySQLConnectorException("Unable to prepare query: " . $query);
        }
        return $stmt;
    }

    public function readStatement(\mysqli_stmt $stmt, int $fetch_mode = MYSQLI_ASSOC): array {
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all($fetch_mode);
        return $result;
    }

    public function getMysqliType($var) {
        if (is_int($var)) return 'i';
        if (is_float($var)) return 'd';
        if (is_string($var)) return 's';
        if (is_null($var)) return 's';
        return 'b';
    }
}


class MySQLConnectorException extends \Exception {
    public function errorMessage() {
        $errorMsg = "MySQLConnector error on line " . $this->getLine() . " in " . $this->getFile()
            . ": <b>" . $this->getMessage() . "</b>";
        return $errorMsg;
    }
}
