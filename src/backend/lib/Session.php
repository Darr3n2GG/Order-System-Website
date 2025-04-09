<?php

namespace lib\Session;

class Session {
    private $log_masuk;

    public function __construct() {
        session_start();
        $this->log_masuk = isset($_SESSION["id_pelanggan"]);
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
