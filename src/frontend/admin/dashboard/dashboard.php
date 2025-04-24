<?php
require_once(dirname(__FILE__, 2) . "/nav_bar/nav_bar.php");
require_once(dirname(__FILE__, 2) . "/admin_header/admin_header.php");
require_once(dirname(__FILE__, 4) . "/backend/Database.php");
require_once(dirname(__FILE__, 4) . "/backend/Masa.php");
require_once(dirname(__FILE__, 4) . "/backend/Autoloader.php");

$Session = new lib\Session;
if (!$Session->isAdmin()) {
    header("Location: ../../menu/menu.php");
}

$Database = createDatabaseConn();

$array_pesanan_id = getArrayPesananIDThisWeek();
if ($array_pesanan_id != []) {
    $produk_popular = getProdukPopular($array_pesanan_id);
    $id_produk = $produk_popular["id_produk"];
    $nama_produk = getNamaProduk($id_produk);
} else {
    $nama_produk = "Kosong";
}

$pesanan_count = getPesananCount();
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
    echoNavBar(NAVBAR_DASHBOARD);
    echoAdminHeader("Dashboard");
    ?>
    <div class="content container">
        Minggu ini :
        <div class="row_kecil">
            <div class="item_kecil">
                <h2>Most ordered food / drink</h2>
                <h1><?php echo $nama_produk; ?></h1>
            </div>
            <div class="item_kecil">
                <h2>Number of orders this week</h2>
                <h1><?php echo $pesanan_count; ?></h1>
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

<?php
function getArrayPesananIDThisWeek(): array {
    $Pesanan = new lib\Pesanan;
    $array_pesanan = $Pesanan->getArrayPesananThisWeek();

    $array_pesanan_id = [];

    foreach ($array_pesanan as $pesanan) {
        $array_pesanan_id[] = $pesanan["id"];
    }

    return $array_pesanan_id;
}

function getProdukPopular(array $array_id_pesanan): array {
    global $Database;

    $in = join(',', array_fill(0, count($array_id_pesanan), '?'));

    return $Database->readQuery(
        "SELECT id_produk, CAST(SUM(kuantiti) AS UNSIGNED) AS jumlah_kuantiti
        FROM belian WHERE id_pesanan IN ( $in ) GROUP BY id_produk ORDER BY jumlah_kuantiti DESC LIMIT 1",
        str_repeat('i', count($array_id_pesanan)),
        $array_id_pesanan
    )[0];
}

function getNamaProduk($id_produk): string {
    global $Database;

    return $Database->readQuery(
        "SELECT nama FROM produk WHERE id = ?",
        "i",
        [$id_produk]
    )[0]["nama"];
}

function getKuantitiPesanan(): int {
    global $Database;

    return $Database->readQuery(
        "SELECT COUNT(*) AS pesanan_count FROM pesanan WHERE tarikh >= ? and tarikh <= ?",
        "ss",
        [getWeekStart(), getWeekEnd()]
    )[0]["pesanan_count"];
}

function getPesananCount(): int {
    global $Database;

    return $Database->readQuery(
        "SELECT COUNT(*) AS pesanan_count FROM pesanan WHERE tarikh >= ? and tarikh <= ?",
        "ss",
        [getWeekStart(), getWeekEnd()]
    )[0]["pesanan_count"];
}
?>