<?php
session_start();

function getUserIDFromSession() {
    if (checkIfLoggedIn() == true) {
        return $_SESSION["user_id"];
    } else {
        return 0; // id 0 is the guest account ( temporary implementation for testing orders )
        // TODO : show popup
    }
}

function checkIfLoggedIn() {
    return isset($_SESSION["user_id"]) || $_SESSION["user_id"] !== 0;
}
