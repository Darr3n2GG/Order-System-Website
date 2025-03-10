<?php
require_once("../header/header.php");
require_once("../../backend/MySQLConnector.php");

$MySQLConnector = new MySQLConnector("localhost", "root", "", "restorandb");
$array_pesanan = $MySQLConnector->readQuery(
    "SELECT pesanan.id as id, akaun.nama as nama, pesanan.tarikh as tarikh,
            status.status as status, pesanan.cara as cara, pesanan.no_meja as no_meja
    FROM pesanan
    INNER JOIN akaun ON pesanan.akaun_id = akaun.id
    INNER JOIN status ON pesanan.status_id = status.id
    "
);
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echoHeaderStylesheet(); ?>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css" />
    <script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/shoelace-autoloader.js"></script>
    <title>Admin</title>
</head>

<body>
    <?php echoHeader(); ?>
    <?php echoNoScript(); ?>
    <div class="main container">
        <?php
        echo <<<TABLE
        <table>
            <tr>
                <th>id</th>
                <th>nama</th>
                <th>tarikh</th>
                <th>status</th>
                <th>cara</th>
            </tr>
        TABLE;
        foreach ($array_pesanan as $pesanan) {
            $id = $pesanan["id"];
            $nama = $pesanan["nama"];
            $tarikh = $pesanan["tarikh"];
            $status = $pesanan["status"];
            $cara = $pesanan["cara"];
            echo <<<DATA
            <tr>
                <td>$id</td>
                <td>$nama</td>
                <td>$tarikh</td>
                <td>$status</td>
                <td>$cara</td>
            </tr>
            DATA;
        }
        // TODO : Use DataTables.js
        ?>
    </div>
</body>

</html>