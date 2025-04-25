<?php
require_once dirname(__FILE__, 3) . "/dependencies.php";

function echoAdminHeaderStylesheet(): void {
    $url = auto_version("/Order-System-Website/src/frontend/admin/admin_header/admin_header.css");
    echo "<link rel='stylesheet' href='$url'>";
}

function echoAdminHeader(string $title): void {
    echo <<<ADMINHEADER
        <header class="admin_header" id="header">
            <div class="admin_header_container">
                <h2>$title</h2>
            </div>
        </header>
    ADMINHEADER;
}
