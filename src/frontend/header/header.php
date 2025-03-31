<?php
// Server rendered header
require_once(dirname(__FILE__, 3) . "/backend/Session.php");
$admin = false;

function echoHeaderStylesheet(): void {
    echo '<link rel="stylesheet" href="/Order-System-Website/src/frontend/header/header.css">';
}

function echoHeader(): void {
    if (checkIfLoggedIn() == true) {
        $guest_nav = "";
        $registered_nav = "show_nav";
    } else {
        $guest_nav = "show_nav";
        $registered_nav = "";
    }

    echo <<<HEADER1
    <header class="header" id="header">
        <nav class="nav container">
            <a href="/Order-System-Website/src/frontend/menu/menu.php">
                <img class="nav_logo" src="/Order-System-Website/src/assets/logo+text.png" alt="Logo">
            </a>
            <div class="nav_guest $guest_nav" id="nav-guest">
                <ul class="nav_list">
                    <li class="nav_item">
                        <sl-button href="../login/login.php">Log Masuk</sl-button>
                    </li>
                    <li class="nav_item">
                        <sl-button variant="primary">Daftar</sl-button>
                    </li>
    HEADER1;
    echoAdminButton(); // Temporary implementation
    echo <<<HEADER2
                </ul>
            </div>
            <div class="nav_registered $registered_nav" id="nav-registered">
                <ul class="nav_list">
                    <li class="nav_item">
                        <a href=#>
                            <sl-avatar class="user_button" label="user button"></sl-avatar>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    HEADER2;
}

function echoAdminButton(): void {
    global $admin;
    if ($admin == true) {
        echo <<<ADMIN
                    <li class="nav_item">
                        <sl-button class="admin_button" variant="neutral">Go to Admin</sl-button>
                    </li>
        ADMIN;
    }
}

function echoAdminButtonScript(): void {
    global $admin;
    if ($admin == true) {
        echo '<script type="module" src="../header/adminButton.js"></script>';
    }
}
