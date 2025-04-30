<?php
require_once dirname(__FILE__, 3) .  "/backend/Database.php";
require_once dirname(__FILE__, 3) . "/backend/Autoloader.php";
require_once dirname(__FILE__, 2) . "/header/header.php";
require_once dirname(__FILE__, 2) . "/dependencies.php";
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelanggan</title>
    <link rel="stylesheet" href="<?php echo auto_version("../style.css"); ?>">
    <link rel="stylesheet" href="<?php echo auto_version("pelanggan.css"); ?>">
    <?php
    echoHeaderStylesheet();
    echoTabulatorStyle();
    echoShoelaceStyle();
    ?>
</head>

<body>
    <?php echoHeader(); ?>
    <div class="content container main_content">
        <div class="user_container">
            <div class="user_info_container">
                <sl-avatar></sl-avatar>
                <div class="user_info">
                    <h1>Nama</h1>
                    <span>0123456789</span>
                </div>
            </div>
            <sl-button id="log_keluar_button" variant="danger">Log Keluar</sl-button>
        </div>
        <div class="table_container">
            <div id="table_senarai_pesanan"></div>
        </div>
    </div>

    <sl-dialog id="dialog_pesanan">

    </sl-dialog>

    <script type="module" src="table_pelanggan.js"></script>
    <?php
    echoShoelaceAutoloader();
    echoTabulator();
    echoNoScript();
    ?>
</body>

</html>