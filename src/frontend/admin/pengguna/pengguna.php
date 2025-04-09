<?php
require_once dirname(__FILE__, 2) . "/nav_bar/nav_bar.php";
require_once dirname(__FILE__, 2) . "/admin_header/admin_header.php";
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css" />
    <script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/shoelace-autoloader.js"></script>
    <link href="https://unpkg.com/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables/dist/js/tabulator.min.js"></script>
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="pengguna.css">
    <?php
    echoAdminHeaderStylesheet();
    echoNavBarStylesheet();
    ?>
    <title>List Pengguna</title>
</head>

<body>
    <?php
    echoNavBar();
    echoAdminHeader("Pengguna");
    ?>
    <div class="content container">
        <div class="table_container">
            <div id="table_pengguna"></div>
        </div>
    </div>
    <script type="module" src="table_pengguna.js"></script>
</body>

</html>