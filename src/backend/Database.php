<?php
require_once(__DIR__ . "/MySQLConnector.php");
require_once(__DIR__ . "/Constants.php");

date_default_timezone_set("Asia/Kuala_Lumpur");

function createDatabaseConn(): MySQLConnector {
    return new MySQLConnector(HOST, USER, PASSWORD, DATABASE);
}
