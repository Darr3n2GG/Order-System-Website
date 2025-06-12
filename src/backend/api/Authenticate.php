<?php
require_once dirname(__FILE__, 2) . "/Database.php";
require_once dirname(__FILE__, 2) . "/Autoloader.php";

session_start();

$Database = createDatabaseConn();

if (!isset($_POST["nama"], $_POST["password"])) {
    exit("Sila masukkan field nama dan password.");
}

try {
    $Pelanggan = new lib\Pelanggan($Database);

    $nama = $_POST["nama"];
    $password = $_POST["password"];

    if (check_nama_exists($nama)) {
        $hashed_password = get_password_from($nama);
        if (password_verify($password, $hashed_password)) {
            $id = get_id_from($nama);
            session_regenerate_id();
            $_SESSION["nama"] = $nama;
            $_SESSION["id_pelanggan"] = $id;
            if ($Pelanggan->getTahapPelanggan($id) === "admin") {
                $redirect_url = "/Order-System-Website/src/frontend/admin/dashboard/dashboard.php";
            } else {
                $redirect_url = "/Order-System-Website/src/frontend/menu/menu.php";
            }
            echo "<script type='text/javascript'>
                alert('Berjaya log masuk!');
                window.location.href = '$redirect_url'
            </script>";
        } else {
            echo "<script type='text/javascript'>
                    alert('Password tidak betul.');
                    window.location.href = '/Order-System-Website/src/frontend/login/login.php';
                </script>";
        }
    } else {
        echo "<script type='text/javascript'>
                alert('Nama tidak wujud.');
                window.location.href = '/Order-System-Website/src/frontend/login/login.php';
            </script>";
    }
} catch (Exception $e) {
    error_log($e->getMessage() . PHP_EOL, 3, dirname(__FILE__, 2) . "/log/error_log.log");
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
