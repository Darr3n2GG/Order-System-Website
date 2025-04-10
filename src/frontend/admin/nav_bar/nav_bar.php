<?php
function echoNavBarStylesheet(): void {
    echo '<link rel="stylesheet" href="/Order-System-Website/src/frontend/admin/nav_bar/nav_bar.css">';
}

function echoNavBar(string $selected): void {
    $dashboard = "";
    $pelanggan = "";
    $menu = "";
    $pesanan = "";

    switch ($selected) {
        case "dashboard":
            $dashboard = "active";
            break;
        case "pelanggan":
            $pelanggan = "active";
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
                    Pengguna
                </a>
            </li>
            <li class="nav_link">
                <a class="$menu" href="#">
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
        </ul>
    NAV;
}
