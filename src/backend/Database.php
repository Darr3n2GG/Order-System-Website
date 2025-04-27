<?php
require_once __DIR__ . "/lib/MySQLConnector.php";
require_once __DIR__ . "/Constants.php";

function createDatabaseConn(): lib\MySQLConnector {
    return new lib\MySQLConnector(HOST, USER, PASSWORD, DATABASE);
}
