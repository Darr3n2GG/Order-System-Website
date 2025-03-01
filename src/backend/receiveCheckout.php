<?php
include_once("account.php");
$user_id = getUserIDFromSession();

$cartStringData = $_POST["cart"];
$cartJSONData = json_decode($checkoutStringData);

// Create pesanan column with N belians


echo json_encode($checkoutJSONData);
