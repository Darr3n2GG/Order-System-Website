<?php
// Server rendered header

require_once dirname(__FILE__, 3) . "/backend/lib/Session.php";
require_once dirname(__FILE__, 2) . "/dependencies.php";

$Session = new lib\Session;

function echoHeaderStylesheet(): void {
    $url = auto_version("/Order-System-Website/src/frontend/header/header.css");
    echo "<link rel='stylesheet' href=$url>";
}

function echoHeader(): void {
    global $Session;

    $html = "";
    if ($Session->sudahLogMasuk() and $_SERVER['REQUEST_URI'] == "/Order-System-Website/src/frontend/pelanggan/pelanggan.php") {
        $html = <<<PELANGGAN
        <div class="nav_registered">
            <ul class="nav_list">
                <li class="nav_item">
                    <sl-button variant="text" href="../menu/menu.php">
                        <sl-icon slot="prefix" name="house"></sl-icon>
                        Balik ke Menu
                    </sl-button>
                </li>
            </ul>
        </div>
        PELANGGAN;
    } else if ($Session->sudahLogMasuk()) {
        $html = <<<DAFTAR
        <div class="nav_registered">
            <ul class="nav_list">
                <li class="nav_item">
                    <a href="../pelanggan/pelanggan.php">
                        <sl-tooltip content="Pelanggan">
                            <sl-avatar class="user_button" label="user button"></sl-avatar>
                        </sl-tooltip>
                    </a>
                </li>
            </ul>
        </div>
        DAFTAR;
    } else {
        $html = <<<GUEST
        <div class="nav_guest">
            <ul class="nav_list">
                <li class="nav_item">
                    <sl-button href="../login/login.php">Log Masuk</sl-button>
                </li>
                <li class="nav_item">
                    <sl-button href="../daftar/daftar.php" variant="primary">Daftar</sl-button>
                </li>
            </ul>
        </div>
        GUEST;
    }

    echo <<<HEADER
    <header class="header" id="header">
        <nav class="nav container">
            <a href="/Order-System-Website/src/frontend/menu/menu.php">
                <img class="nav_logo" src="/Order-System-Website/src/assets/logo+text.png" alt="Logo">
            </a>
            $html
        </nav>
    </header>
    HEADER;
}
