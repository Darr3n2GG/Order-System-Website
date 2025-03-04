<?php
require_once("makanan.php");

try {
    $makanan_id = isset($_GET["id"]) ? $_GET["id"] : 0;
    $objek_makanan = new Makanan;
    $makanan = $objek_makanan->getMakanan($makanan_id);
    echo json_encode($makanan[0]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Message : " . $e->getMessage()]);
}
