<?php

namespace lib;

require_once dirname(__FILE__) . "/Pelanggan.php";

class Session {
    private $log_masuk;
    private $admin;

    public function __construct() {
        session_start();

        $this->log_masuk = !isset($_SESSION["id_pelanggan"]) ? false : $this->checkIdPelangganValid();
    }

    private function checkIdPelangganValid(): bool {
        $Pelanggan = new Pelanggan;

        if ($Pelanggan->checkPelangganExists($_SESSION["id_pelanggan"])) {
            return true;
        } else {
            unset($_SESSION["id_pelanggan"]);
            return false;
        }
    }

    public function sudahLogMasuk() {
        return $this->log_masuk;
    }

    public function getPelangganIDFromSession() {
        if ($this->log_masuk) {
            return $_SESSION["id_pelanggan"];
        } else {
            return 0; // id 0 is the guest account ( temporary implementation for testing orders )
            // TODO : show popup
        }
    }
}
