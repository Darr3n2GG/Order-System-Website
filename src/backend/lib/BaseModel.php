<?php

namespace lib;

require_once dirname(__FILE__, 2) . "/Database.php";

class BaseModel {
    protected $Database;

    public function __construct() {
        $this->Database = createDatabaseConn();
    }

    protected function deleteById(string $table, int $id): bool {
        return $this->Database->executeQuery(
            "DELETE FROM $table WHERE id = ?",
            "i",
            [$id]
        );
    }
}
