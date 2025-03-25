<?php
define("SECONDS_IN_A_DAY", 86400);

function getWeekStart(): string {
    $day = date("w");
    return date(DATE_FORMAT, strtotime("-" . $day . " days"));
}

function getWeekEnd(): string {
    $day = date("w");
    return date(DATE_FORMAT, strtotime("+" . (6 - $day) . " days"));
}
