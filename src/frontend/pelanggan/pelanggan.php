<?php
require_once dirname(__FILE__, 3) .  "/backend/Database.php";
require_once dirname(__FILE__, 3) . "/backend/Autoloader.php";
require_once dirname(__FILE__, 2) . "/header/header.php";
require_once dirname(__FILE__, 2) . "/dependencies.php";

$Session = new lib\Session;

if (!$Session->sudahLogMasuk()) {
    header("Location: /Order-System-Website/src/frontend/menu/menu.php");
}

?>

<!DOCTYPE html>
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
                    <h1><?php echo $Session->getNama(); ?></h1>
                    <span><?php echo $Session->getNomborPhone(); ?></span>
                </div>
            </div>
            <sl-button id="log_keluar_button" variant="danger">Log Keluar</sl-button>
        </div>
        <div class="table_container">
            <aside id="filter_sidebar">
                <h2>Senarai Pesanan</h2>
                <div class="filter">
                    <div id="time_range">
                        <span>Julat Masa</span>
                        <div id="time_range_filter" class="filter">
                            <div class="filter_item">
                                <span>Dari:</span>
                                <sl-input id="time_dari" size="small" type="date"></sl-input>
                            </div>
                            <div class="filter_item">
                                <span>Hingga:</span>
                                <sl-input id="time_hingga" size="small" type="date"></sl-input>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <div data-id_pelanggan="<?php echo $Session->getIDPelanggan(); ?>" id="table_senarai_pesanan"></div>
        </div>
    </div>
    <div id="receiptModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); width:80%; max-width:600px; background:white; padding:20px; border:1px solid #ccc; box-shadow:0 0 10px rgba(0,0,0,0.3); z-index:9999;">
        <div style="text-align:right;">
            <button id="print_receipt_button">Cetak</button>
            <button id="close_receipt_button">Tutup</button>
        </div>
        <iframe id="receiptFrame" name="receiptFrame" src="" style="width:100%; height:400px; border:none;"></iframe>
    </div>


    <script type="module" src="<?php echo auto_version("pelanggan.js"); ?>"></script>
    <script type="module" src="<?php echo auto_version("table_pelanggan.js"); ?>"></script>
    <?php
    echoShoelaceAutoloader();
    echoTabulator();
    echoNoScript();
    ?>
</body>

</html>