<?php
require_once dirname(__FILE__, 2) . "/Database.php";

session_start();

$Database = createDatabaseConn();
$redirect_url = "../../frontend/menu/menu.php";

if (!isset($_POST["nama"], $_POST["password"])) {
    exit("Sila masukkan field nama dan password.");
}

try {
    $nama = $_POST["nama"];
    $password = $_POST["password"];

    if (check_nama_exists($nama)) {
        $hashed_password = get_password_from($nama);
        if (password_verify($password, $hashed_password)) {
            $id = get_id_from($nama);
            session_regenerate_id();
            $_SESSION["nama"] = $nama;
            $_SESSION["id_pelanggan"] = $id;
            header("Location: " . $redirect_url);
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage() . PHP_EOL, dirname(__FILE__, 2) . "/log/error_log.log");
    exit($e->getMessage());
}

function check_nama_exists(string $nama): bool {
    global $Database;

    $stmt = $Database->prepareStatement("SELECT id FROM pelanggan WHERE nama = ?");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows == 1;
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
