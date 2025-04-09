<?php
require_once __DIR__ . "/Constants.php";

function getWeekStart(): string {
    $day = date("w");
    return date(DATE_FORMAT, strtotime("-" . $day . " days"));
}

function getWeekEnd(): string {
    $day = date("w");
    return date(DATE_FORMAT, strtotime("+" . (6 - $day) . " days"));
}
