<?php
require_once("../../backend/Session.php");

if (checkIfLoggedIn() == true) {
    $guest_nav = "";
    $registered_nav = "show_nav";
} else {
    $guest_nav = "show_nav";
    $registered_nav = "";
}


// Server rendered header
echo <<<HEADER
    <link rel="stylesheet" href="/Order-System-Website/src/frontend/header/header.css">
    <header class="header" id="header">
        <nav class="nav container">
            <a href="#">
                <img class="nav_logo" src="/Order-System-Website/src/assets/logo+text.png" alt="Logo">
            </a>
            <div class="nav_guest $guest_nav" id="nav-guest">
                <ul class="nav_list">
                    <li class="nav_item">
                        <sl-button>Log Masuk</sl-button>
                    </li>
                    <li class="nav_item">
                        <sl-button variant="primary">Daftar</sl-button>
                    </li>
                </ul>
            </div>
            <div class="nav_registered $registered_nav" id="nav-registered">
                <ul class="nav_list">
                    <li class="nav_item">
                        <sl-icon-button
                            class="icon" name="person" label="User icon">
                        </sl-icon-button>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <noscript>Your browser does not support JavaScript!</noscript>
HEADER;
