<?php
require_once(__DIR__ . "/MySQLConnector.php");

date_default_timezone_set("Asia/Kuala_Lumpur");

define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "");
define("DATABASE", "restorandb");

function DatabaseFactory(): MySQLConnector {
    return new MySQLConnector(HOST, USER, PASSWORD, DATABASE);
}
