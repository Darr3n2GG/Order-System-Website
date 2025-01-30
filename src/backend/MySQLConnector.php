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

    public function readQuery(string $query): array {
        try {
            $stmt = $this->prepareQuery($query);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            echo "Message : " . $e->getMessage();
        }
    }

    public function executeQuery(string $query, string $types, array $array_of_params): void {
        try {
            $stmt = $this->prepareQuery($query);
            $stmt->bind_param($types, ...$array_of_params);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Message : " . $e->getMessage();
        }
        $stmt->close();
    }

    private function prepareQuery(string $query): mysqli_stmt {
        $stmt = $this->conn->prepare($query);
        if ($stmt == false) {
            throw new Exception("Unable to prepare query: " . $query);
        }
        return $stmt;
    }
}
