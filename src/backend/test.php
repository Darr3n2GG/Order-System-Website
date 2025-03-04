<?php
require_once("MySQLConnector.php");
$MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
$id_pesanan = $MySQLConnector->readLastInsertedID();
echo $id_pesanan;
