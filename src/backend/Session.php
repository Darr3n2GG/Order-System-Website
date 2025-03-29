<?php
session_start();

function getPelangganIDFromSession() {
    if (checkIfLoggedIn() == true) {
        return $_SESSION["id_pelanggan"];
    } else {
        return 0; // id 0 is the guest account ( temporary implementation for testing orders )
        // TODO : show popup
    }
}

function checkIfLoggedIn() {
    return isset($_SESSION["id_pelanggan"]);
}
