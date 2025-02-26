<?php
$checkoutStringData = $_POST["cart"];
$checkoutJSONData = json_decode($checkoutStringData);
echo json_encode($checkoutJSONData);
