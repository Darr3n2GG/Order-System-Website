<?php
require_once dirname(__FILE__, 3) . "/dependencies.php";

define("NAVBAR_DASHBOARD", 1);
define("NAVBAR_PELANGGAN", 2);
define("NAVBAR_MENU", 3);
define("NAVBAR_PESANAN", 4);

function echoNavBarStylesheet(): void {
    $url = auto_version("/Order-System-Website/src/frontend/admin/nav_bar/nav_bar.css");
    echo "<link rel='stylesheet' href='$url'>";
}

function echoNavBar(int $selected): void {
    $dashboard = "";
    $pelanggan = "";
    $menu = "";
    $pesanan = "";

    switch ($selected) {
        case NAVBAR_DASHBOARD:
            $dashboard = "active";
            break;
        case NAVBAR_PELANGGAN:
            $pelanggan = "active";
            break;
        case NAVBAR_MENU:
            $menu = "active";
            break;
    }

    echo <<<NAV
    <ul class="side_nav">
        <img class="nav_logo" src="/Order-System-Website/src/assets/logo+text.png" alt="Logo">
        <li class="nav_link">
            <a class="$dashboard" href="/Order-System-Website/src/frontend/admin/dashboard/dashboard.php">
                <sl-icon name="graph-up-arrow"></sl-icon>
                Dashboard
            </a>
        </li>
        <li class="nav_link">
            <a class="$pelanggan" href="/Order-System-Website/src/frontend/admin/pelanggan/pelanggan.php">
                <sl-icon name="person-gear"></sl-icon>
                Pelanggan
            </a>
        </li>
        <li class="nav_link">
            <a class="$menu" href="/Order-System-Website/src/frontend/admin/menu/senarai_menu.php">
                <sl-icon name="menu-button-wide"></sl-icon>
                Menu
            </a>
        </li>
        <li class="nav_link">
            <a class="$pesanan" href="#">
                <sl-icon name="book"></sl-icon>
                Pesanan
            </a>
        </li>
        <li class="log_keluar">
            <sl-button id="log_keluar_button" variant="danger">Log Keluar</sl-button>
        </li>
    </ul>
    NAV;
}

function echoNavBarJavascript(): void {
    $url = auto_version("/Order-System-Website/src/frontend/admin/nav_bar/nav_bar.js");
    echo "<script type='module' src='$url'></script>";
}
