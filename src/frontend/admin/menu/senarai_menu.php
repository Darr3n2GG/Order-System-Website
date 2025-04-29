<?php
require_once dirname(__FILE__, 2) . "/adminBootstrap.php";
require_once dirname(__FILE__, 3) . "/dependencies.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css" />
    <link rel="stylesheet" href="<?php echo auto_version("../../style.css"); ?>">
    <link rel="stylesheet" href="<?php echo auto_version(".css"); ?>">
    <?php
    echoAdminHeaderStylesheet();
    echoNavBarStylesheet();
    ?>
    <title>Senarai Menu</title>
</head>

<body>
    <?php
    echoNavBar(NAVBAR_MENU);
    echoAdminHeader("Senarai Menu");
    ?>
    <div class="content container">
    </div>

    <script type="module" src="<?php echo auto_version(".js"); ?>"></script>
    <?php
    echoShoelaceAutoloader();
    ?>
</body>

</html>