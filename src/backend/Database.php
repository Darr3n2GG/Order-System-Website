<?php
require_once(__DIR__ . "/MySQLConnector.php");

date_default_timezone_set("Asia/Penang");

function DatabaseFactory(): MySQLConnector {
    $HOST = "localhost";
    $USER = "root";
    $PASSWORD = "";
    $DATABASE = "restorandb";
    return new MySQLConnector($HOST, $USER, $PASSWORD, $DATABASE);
}
