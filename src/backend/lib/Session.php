<?php

namespace lib;

require_once dirname(__FILE__) . "/Pelanggan.php";

class Session {
    private bool $log_masuk = false;
    private bool $admin = false;
    private int $id_pelanggan;
    private string $nama;
    private string $no_phone;

    private Pelanggan $Pelanggan;

    public function __construct() {
        $this->Pelanggan = new Pelanggan;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION["id_pelanggan"])) $this->log_masuk = $this->checkIdPelangganValid();

        if ($this->log_masuk) {
            $this->id_pelanggan = $_SESSION["id_pelanggan"];
            $this->nama = $this->Pelanggan->findPelanggan($this->id_pelanggan)["nama"];
            $this->no_phone = $this->Pelanggan->findPelanggan($this->id_pelanggan)["no_phone"];
            $this->setAdmin();
        }
    }

    private function checkIdPelangganValid(): bool {

        if ($this->Pelanggan->checkPelangganExists($_SESSION["id_pelanggan"])) {
            return true;
        } else {
            session_destroy();
            return false;
        }
    }

    private function setAdmin(): void {
        $this->admin = $this->Pelanggan->getTahapPelanggan($this->id_pelanggan) === "admin";
    }

    public function sudahLogMasuk() {
        return $this->log_masuk;
    }

    public function isAdmin(): bool {
        return $this->admin;
    }

    public function getNama(): string {
        return $this->nama;
    }

    public function getNomborPhone(): string {
        return $this->no_phone;
    }

    public function getIDPelanggan() {
        if ($this->log_masuk) {
            return $this->id_pelanggan;
        } else {
            return 0; // id 0 is the guest account ( temporary implementation for testing orders )
            // TODO : show popup
        }
    }
}
