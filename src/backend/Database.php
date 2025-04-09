<?php
require_once __DIR__ . "/lib/MySQLConnector.php";
require_once __DIR__ . "/Constants.php";

function createDatabaseConn(): MySQLConnector {
    return new MySQLConnector(HOST, USER, PASSWORD, DATABASE);
}
