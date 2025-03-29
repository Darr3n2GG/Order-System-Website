<?php
require_once(dirname(__FILE__, 2) . "/header/header.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php echoHeaderStylesheet(); ?>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css" />
    <script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/shoelace-autoloader.js"></script>
</head>

<body>
    <?php echoHeader(); ?>
    <div class="main container content">
        <h2>Login</h2>
        <form action="/Order-System-Website/src/backend/Authenticate.php" method="post">
            <div class="form_group">
                <label for="nama">Nama</label>
                <div class="form_input">
                    <sl-input id="nama" type="text" name="nama" placeholder="Masukkan nama" required></sl-input>
                </div>
            </div>
            <div class="form_group">
                <label for="password">Password</label>
                <div class="form_input">
                    <sl-input id="password" type="password" name="password" placeholder="Masukkan password" required=""></sl-input>
                </div>
            </div>
            <!-- <div class="form_group">
                <label for="phone">Nombor Phone</label>
                <div class="form_input">
                    <sl-input id="phone" type="tel" name="phone" placeholder="Masukkan nombor phone" required=""></sl-input>
                </div>
            </div> -->
            <sl-button class="login_button" type="submit">Login</sl-button>
            <sl-button href="" variant="text">Register</sl-button>
        </form>
    </div>
    <noscript>Your browser does not support JavaScript!</noscript>
</body>

</html>