<?php

namespace lib;

require_once dirname(__FILE__) . "/Pelanggan.php";

class Session {
    private $log_masuk = false;
    private $admin = false;
    private $id_pelanggan;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION["id_pelanggan"])) $this->log_masuk = $this->checkIdPelangganValid();

        if ($this->log_masuk) {
            $this->id_pelanggan = $_SESSION["id_pelanggan"];
            $this->setAdmin();
        }
    }

    private function checkIdPelangganValid(): bool {
        $Pelanggan = new Pelanggan;

        if ($Pelanggan->checkPelangganExists($_SESSION["id_pelanggan"])) {
            return true;
        } else {
            session_destroy();
            return false;
        }
    }

    private function setAdmin(): void {
        $Pelanggan = new Pelanggan;
        $this->admin = $Pelanggan->getTahapPelanggan($this->id_pelanggan) === "admin";
    }

    public function sudahLogMasuk() {
        return $this->log_masuk;
    }

    public function isAdmin(): bool {
        return $this->admin;
    }

    public function getPelangganIDFromSession() {
        if ($this->log_masuk) {
            return $this->id_pelanggan;
        } else {
            return 0; // id 0 is the guest account ( temporary implementation for testing orders )
            // TODO : show popup
        }
    }
}
