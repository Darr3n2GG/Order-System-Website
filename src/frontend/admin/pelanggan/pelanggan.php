<?php
require_once dirname(__FILE__, 2) . "/nav_bar/nav_bar.php";
require_once dirname(__FILE__, 2) . "/admin_header/admin_header.php";
require_once dirname(__FILE__, 3) . "/dependencies.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css" />
    <link href="https://unpkg.com/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo auto_version("../../style.css"); ?>">
    <link rel="stylesheet" href="<?php echo auto_version("pelanggan.css"); ?>">
    <?php
    echoAdminHeaderStylesheet();
    echoNavBarStylesheet();
    ?>
    <title>List Pelanggan</title>
</head>

<body>
    <?php
    echoNavBar(NAVBAR_PELANGGAN);
    echoAdminHeader("List Pelanggan");
    ?>
    <div class="content container">
        <div class="table_container">
            <div id="table_pelanggan"></div>
        </div>
        <div class="toolbar">
            <h2>Toolbar</h2>
            <form class="pelanggan_form" action="">
                <sl-input placeholder="Nama" id="nama" name="nama" required>
                    <sl-icon name="person-circle" slot="prefix"></sl-icon>
                </sl-input>
                <sl-input placeholder="Nombor Phone" id="no_phone" name="no_phone" required>
                    <sl-icon name="telephone" slot="prefix"></sl-icon>
                </sl-input>
                <sl-input placeholder="Password" id="password" name="password" required>
                    <sl-icon name="key" slot="prefix"></sl-icon>
                </sl-input>
                <sl-button type="submit">
                    <sl-icon slot="prefix" name="plus-square"></sl-icon>
                    Tambah pelanggan
                </sl-button>
                <sl-button class="csv_input">
                    Import CSV
                </sl-button>
                <ul class="files_list"></ul>
            </form>
        </div>
    </div>
    <script type="module" src="<?php echo auto_version("table_pelanggan.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("pelanggan.js"); ?>"></script>
    <?php
    echoTabulator();
    echoShoelaceAutoloader();
    ?>
</body>

</html>