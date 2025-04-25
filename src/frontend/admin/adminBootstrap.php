<?php
require_once dirname(__FILE__, 3) . "/backend/Autoloader.php";
require_once __DIR__ . "/nav_bar/nav_bar.php";
require_once __DIR__ . "/admin_header/admin_header.php";

$Session = new lib\Session;
if (!$Session->isAdmin()) {
    header("Location: ../../menu/menu.php");
}
