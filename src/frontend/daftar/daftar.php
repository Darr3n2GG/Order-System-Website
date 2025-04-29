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
    <link rel="stylesheet" href="<?php echo auto_version("../style.css"); ?>">
    <link rel="stylesheet" href="<?php echo auto_version("daftar.css"); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css" />
</head>

<body>
    <div class="content">
        <div class="image_container">
            <img src="../../assets/gong.png" alt="Logo">
        </div>
        <div class="form_container">
            <div class="form_header">
                <h2>Daftar</h2>
                <sl-button class="balik_menu_button" variant="text" href="/Order-System-Website/src/frontend/menu/menu.php">
                    <span>Balik Menu</span>
                    <sl-icon slot="prefix" name="house"></sl-icon>
                </sl-button>
            </div>
            <form class="form_daftar" action="/Order-System-Website/src/backend/api/Daftar.php" method="post">
                <div class="form_group">
                    <div class="form_input">
                        <sl-input id="nama" autocomplete="username" type="text" name="nama" placeholder="Masukkan nama" pill>
                            <label slot="label">Nama</label>
                            <sl-icon name="person" slot="prefix"></sl-icon>
                        </sl-input>
                    </div>
                </div>

                <div class="form_group">
                    <div class="form_input">
                        <sl-input id="phone" type="tel" name="phone" placeholder="Masukkan nombor phone" pill>
                            <label slot="label">Nombor Phone</label>
                            <sl-icon name="telephone" slot="prefix"></sl-icon>
                        </sl-input>
                    </div>
                </div>

                <div class="form_group">
                    <div class="form_input">
                        <sl-input id="password" type="password" autocomplete="current-password" name="password" placeholder="Masukkan password" password-toggle pill>
                            <label slot="label">Password</label>
                            <sl-icon name="key" slot="prefix"></sl-icon>
                        </sl-input>
                    </div>
                </div>
                <div>
                    <sl-button class="daftar_button" type="submit">Daftar</sl-button>
                    <sl-button href="../login/login.php" variant="text">Log Masuk</sl-button>
                </div>
            </form>
        </div>
    </div>
    <script type="module" src="<?php echo auto_version("daftar.js"); ?>"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/shoelace-autoloader.js"></script>
    <noscript>Your browser does not support JavaScript!</noscript>
</body>

</html>