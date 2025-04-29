<?php
require_once dirname(__FILE__, 2) . "/Database.php";
require_once dirname(__FILE__, 2) . "/Autoloader.php";

session_start();

if (!isset($_POST["nama"], $_POST["phone"], $_POST["password"])) {
    exit("Sila masukkan field nama, nombor phone dan password.");
}

try {
    $Database = createDatabaseConn();
    $Pelanggan = new lib\Pelanggan;

    $nama = $_POST["nama"];
    $no_phone = $_POST["phone"];
    $password = $_POST["password"];

    if (check_account_exists($nama)) {
        insert_new_account($nama, $no_phone, $password);
    }

} catch (Exception $e) {
    error_log($e->getMessage() . PHP_EOL, dirname(__FILE__, 2) . "/log/error_log.log");
    exit($e->getMessage());
}

function check_account_exists($nama) {
    if (check_nama_exists($nama)) {
        echo "<script type='text/javascript'>
                alert('Nama sudah wujud, sila pilih nama yang lain.');
                window.location.href = '/Order-System-Website/src/frontend/daftar/daftar.php';
            </script>";
    }
    return true;
}

function check_nama_exists(string $nama): bool {
    global $Database;

    $stmt = $Database->prepareStatement("SELECT id FROM pelanggan WHERE nama = ?");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows == 1;
}

function insert_new_account($nama, $no_phone, $password) {
    $redirect_url = "/Order-System-Website/src/frontend/menu/menu.php";

    global $Pelanggan;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $Pelanggan->addPelanggan($nama, $hashed_password, $no_phone);

    echo "<script type='text/javascript'>
            alert('Pelanggan didaftar! Redirecting...');
            window.location.href = '$redirect_url';
        </script>";
}