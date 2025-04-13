<?php

function echoShoelaceStyle(): void {
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/themes/light.css"/>';
}

function echoShoelaceAutoloader(): void {
    echo '<script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.19.1/cdn/shoelace-autoloader.js"></script>';
}

function echoTabulatorStyle(): void {
    echo '<link href="https://unpkg.com/tabulator-tables/dist/css/tabulator.min.css" rel="stylesheet">';
}

function echoTabulator(): void {
    echo '<script type="text/javascript" src="https://unpkg.com/tabulator-tables/dist/js/tabulator.min.js"></script>';
}

function echoChartJS(): void {
    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
}

function echoNoScript(): void {
    echo '<noscript>Your browser does not support JavaScript!</noscript>';
}

/**
 *  Given a file, i.e. /css/base.css, replaces it with a string containing the
 *  file's mtime, i.e. /css/base.1221534296.css.
 *
 *  @param $file  The file to be loaded. works on all type of paths.
 */
function auto_version($file) {
    if ($file[0] !== '/') {
        $file = rtrim(str_replace(DIRECTORY_SEPARATOR, '/', dirname($_SERVER['PHP_SELF'])), '/') . '/' . $file;
    }

    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $file)) {
        return $file;
    }

    $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
    return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}
