<?php
require_once dirname(__FILE__, 2) . "/header/header.php";
require_once dirname(__FILE__, 3) . "/backend/lib/Session.php";
require_once dirname(__FILE__, 2) . "/dependencies.php";

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
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="<?php echo auto_version("login.css"); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css" />
</head>

<body>
    <div class="content">
        <div class="form_container">
            <div class="form_header">
                <h2>Login</h2>
                <sl-button class="balik_menu_button" variant="text" href="/Order-System-Website/src/frontend/menu/menu.php">
                    <span>Balik Menu</span>
                    <sl-icon slot="prefix" name="house"></sl-icon>
                </sl-button>
            </div>
            <form class="form_login" action="/Order-System-Website/src/backend/api/Authenticate.php" method="post">
                <div class="form_group">
                    <div class="form_input">
                        <sl-input id="nama" autocomplete="username" type="text" name="nama" placeholder="Masukkan nama" pill required>
                            <label slot="label">Nama</label>
                            <sl-icon name="person" slot="prefix"></sl-icon>
                        </sl-input>
                    </div>
                </div>

                <!-- <div class="form_group">
                    <label for="phone">Nombor Phone</label>
                    <div class="form_input">
                        <sl-input id="phone" type="tel" name="phone" placeholder="Masukkan nombor phone" required=""></sl-input>
                    </div>
                </div> -->

                <div class="form_group">
                    <div class="form_input">
                        <sl-input id="password" type="password" autocomplete="current-password" name="password" placeholder="Masukkan password" password-toggle pill required>
                            <label slot="label">Password</label>
                            <sl-icon name="key" slot="prefix"></sl-icon>
                        </sl-input>
                    </div>
                </div>
                <div>
                    <sl-button class="login_button" type="submit">Log Masuk</sl-button>
                    <sl-button href="" variant="text">Daftar</sl-button>
                </div>
            </form>
        </div>
    </div>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/shoelace-autoloader.js"></script>
    <noscript>Your browser does not support JavaScript!</noscript>
</body>

</html>