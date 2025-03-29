<?php
require_once(__DIR__ . "/Database.php");

session_start();

$Database = createDatabaseConn();
$MenuURL = "../frontend/menu/menu.php";

if (!isset($_POST["nama"], $_POST["password"])) {
    exit("Please fill both the name and password fields!");
}

$nama = $_POST["nama"];
$password = $_POST["password"];

if (check_nama_exists($nama)) {
    $hashed_password = get_password_from($nama);
    if (password_verify($password, $hashed_password)) {
        $id = get_id_from($nama);
        session_regenerate_id();
        $_SESSION["nama"] = $nama;
        $_SESSION["id_pelanggan"] = $id;
        header("Location: " . $MenuURL);
    }
}

function check_nama_exists(string $nama): bool {
    global $Database;

    $result = $Database->readQuery(
        "SELECT id FROM pelanggan WHERE nama = ?",
        "s",
        [$nama]
    );

    return $result ? true : false;
}

function get_password_from(string $nama): string {
    global $Database;

    $result = $Database->readQuery(
        "SELECT password FROM pelanggan WHERE nama = ?",
        "s",
        [$nama]
    );

    return $result[0]["password"];
}

function get_id_from(string $nama): string {
    global $Database;

    $result = $Database->readQuery(
        "SELECT id FROM pelanggan WHERE nama = ?",
        "s",
        [$nama]
    );

    return $result[0]["id"];
}

function get_nom_phone_from(string $no_phone): string {
    global $Database;

    $result = $Database->readQuery(
        "SELECT id FROM pelanggan WHERE no_phone = ?",
        "s",
        [$no_phone]
    );

    return $result[0]["no_phone"];
}
