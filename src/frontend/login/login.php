<?php
require_once dirname(__FILE__, 2) . "/header/header.php";
require_once dirname(__FILE__, 3) . "/backend/lib/Session.php";

$redirect_url = "../../frontend/menu/menu.php";

$Session = new lib\Session;

if ($Session->sudahLogMasuk()) {
    header("Location: " . $redirect_url);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="refresh" content="300">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php echoHeaderStylesheet(); ?>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css" />
</head>

<body>
    <?php echoHeader(); ?>
    <div class="main container content">
        <h2>Login</h2>
        <form action="/Order-System-Website/src/backend/api/Authenticate.php" method="post">
            <div class="form_group">
                <label for="input_nama">Nama</label>
                <div class="form_input">
                    <sl-input id="input_nama" type="text" name="nama" placeholder="Masukkan nama" required></sl-input>
                </div>
            </div>

            <!-- <div class="form_group">
                <label for="phone">Nombor Phone</label>
                <div class="form_input">
                    <sl-input id="phone" type="tel" name="phone" placeholder="Masukkan nombor phone" required=""></sl-input>
                </div>
            </div> -->

            <div class="form_group">
                <label for="input_password">Password</label>
                <div class="form_input">
                    <sl-input id="input_password" type="password" name="password" placeholder="Masukkan password" required=""></sl-input>
                </div>
            </div>
            <sl-button class="login_button" type="submit">Log Masuk</sl-button>
            <sl-button href="" variant="text">Daftar</sl-button>
        </form>
    </div>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/shoelace-autoloader.js"></script>
    <noscript>Your browser does not support JavaScript!</noscript>
</body>

</html>