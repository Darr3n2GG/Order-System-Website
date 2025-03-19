<?php
function echoAdminHeaderStylesheet(): void {
    echo '<link rel="stylesheet" href="/Order-System-Website/src/frontend/admin/admin_header/admin_header.css">';
}

function echoAdminHeader(string $title): void {
    echo <<<ADMINHEADER
        <header class="header" id="header">
            <nav class="nav">
                <h1>$title</h1>
            </nav>
        </header>
    ADMINHEADER;
}
