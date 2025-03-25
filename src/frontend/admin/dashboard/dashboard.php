<?php
require_once(dirname(__FILE__, 2) . "/nav_bar/nav_bar.php");
require_once(dirname(__FILE__, 2) . "/admin_header/admin_header.php");
require_once(dirname(__FILE__, 4) . "/backend/Database.php");
require_once(dirname(__FILE__, 4) . "/backend/Masa.php");

$Database = createDatabaseConn();

$array_pesanan_id = getArrayPesananIDThisWeek();

$belian = getArrayBelianFromArrayID($array_pesanan_id)[0];

$id_produk = $belian["id_produk"];
$nama_produk = $Database->readQuery(
    "SELECT nama FROM produk WHERE id = ?",
    "i",
    [$id_produk]
);

function getArrayBelianFromArrayID(array $array_id): array {
    global $Database;

    $in = join(',', array_fill(0, count($array_id), '?'));

    $array_belian = $Database->readQuery(
        "SELECT id_produk, 
        CAST(SUM(kuantiti) AS UNSIGNED) AS jumlah_kuantiti
        FROM belian
        WHERE id_pesanan IN ( $in )
        GROUP BY id_produk
        ORDER BY jumlah_kuantiti DESC
        LIMIT 1
        ",
        str_repeat('i', count($array_id)),
        $array_id
    );

    return $array_belian;
}

function getArrayPesananIDThisWeek(): array {
    global $Database;

    $week_start = getWeekStart();
    $week_end = getWeekEnd();

    $array_pesanan = $Database->readQuery(
        "SELECT id FROM pesanan
        WHERE tarikh >= ? and tarikh < ?
        ORDER BY tarikh ASC",
        "ss",
        [$week_start, $week_end]
    );

    $array_pesanan_id = [];

    foreach ($array_pesanan as $pesanan) {
        array_push($array_pesanan_id, $pesanan["id"]);
    }

    return $array_pesanan_id;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="dashboard.css">
    <?php
    echoNavBarStylesheet();
    echoAdminHeaderStylesheet();
    ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css" />
    <script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/shoelace-autoloader.js"></script>
    <link href="https://unpkg.com/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables/dist/js/tabulator.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Admin</title>
</head>

<body>
    <?php
    echoNavBar();
    echoAdminHeader("Dashboard");
    ?>
    <div class="content container">
        <div class="row_kecil">
            <div class="item_kecil">
                Most ordered food / drink is <?php echo $nama_produk[0]["nama"]; ?>
            </div>
        </div>
        <div class="row_besar">
            <div class="item_besar">
                <h2>Table Pesanan</h2>
                <div id="table_pesanan"></div>
            </div>
            <div class="item_besar">
                <div class="container_carta">
                    <h2>Carta Revenue</h2>
                    <canvas id="carta_revenue"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="table_pesanan.js"></script>
    <script type="module" src="carta_revenue.js"></script>
    <noscript>Your browser does not support JavaScript!</noscript>
</body>

</html>