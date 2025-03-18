<?php
function echoNavBarStylesheet(): void {
    echo '<link rel="stylesheet" href="/Order-System-Website/src/frontend/admin/nav_bar/nav_bar.css">';
}

function echoNavBar(): void {
    echo <<<NAV
        <ul class="side_nav">
            <a href="#">
                <img class="nav_logo" src="/Order-System-Website/src/assets/logo+text.png" alt="Logo">
            </a>
            <li class="nav_link">
                <a class="active" href="#">
                    <sl-icon name="graph-up-arrow"></sl-icon>
                    Dashboard
                </a>
            </li>
            <li class="nav_link">
                <a href="#">
                    <sl-icon name="person-gear"></sl-icon>
                    Pengguna
                </a>
            </li>
            <li class="nav_link">
                <a href="#">
                    <sl-icon name="menu-button-wide"></sl-icon>
                    Menu
                </a>
            </li>
            <li class="nav_link">
                <a href="#">
                    <sl-icon name="book"></sl-icon>
                    Pesanan
                </a>
            </li>
        </ul>
    NAV;
}
