<?php

namespace lib;

require_once dirname(__FILE__, 2) . "/Database.php";
require_once dirname(__FILE__, 2) . "/Masa.php";

class Pesanan {
    private $Database;

    public function __construct() {
        $this->Database = createDatabaseConn();
    }

    public function getSemuaPesanan(): array {
        return $this->Database->readQuery(
            "SELECT pesanan.id as id, pelanggan.nama as nama, pesanan.tarikh as tarikh,
            status.status as status, pesanan.cara as cara, pesanan.no_meja as no_meja
            FROM pesanan
            INNER JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id
            INNER JOIN status ON pesanan.id_status = status.id"
        );
    }

    public function getArrayPesananFromRange(string $from, string $to): array {
        return $this->Database->readQuery(
            "SELECT pesanan.id as id, pelanggan.nama as nama, pesanan.tarikh as tarikh,
            status.status as status, pesanan.cara as cara, pesanan.no_meja as no_meja
            FROM pesanan
            INNER JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id
            INNER JOIN status ON pesanan.id_status = status.id
            WHERE tarikh >= ? and tarikh <= ?
            ORDER BY tarikh ASC",
            "ss",
            [$from, $to]
        );
    }

    public function getArrayPesananThisWeek(): array {
        $week_start = getWeekStart();
        $week_end = getWeekEnd();

        return $this->getArrayPesananFromRange($week_start, $week_end);
    }

    public function getPesananByID(int $id): array {
        return $this->Database->readQuery(
            "SELECT pesanan.id as id, pelanggan.nama as nama, pesanan.tarikh as tarikh,
            status.status as status, pesanan.cara as cara, pesanan.no_meja as no_meja
            FROM pesanan WHERE id = ? ORDER BY id ASC",
            "i",
            [$id]
        );
    }

    public function getPesananByIDPelanggan(int $id): array {
        return $this->Database->readQuery(
            "SELECT pesanan.id as id, pelanggan.nama as nama, pesanan.tarikh as tarikh,
            status.status as status, pesanan.cara as cara, pesanan.no_meja as no_meja
            FROM pesanan WHERE id_pelanggan = ? ORDER BY id ASC",
            "i",
            [$id]
        );
    }

    public function getPesananByIDPelangganWithFilter(int $id, string $from, string $to): array {
        return $this->Database->readQuery(
            "SELECT pesanan.id as id, pelanggan.nama as nama, pesanan.tarikh as tarikh,
            status.status as status, pesanan.cara as cara, pesanan.no_meja as no_meja
            FROM pesanan WHERE id_pelanggan = ? and tarikh >= ? and tarikh <= ?
            ORDER BY id ASC",
            "iss",
            [$id, $from, $to]
        );
    }

    public function getArrayPesananFromArrayID(array $array_id): array {
        $array_pesanan = [];

        foreach ($array_id as $id) {
            $pesanan = $this->getPesananByID($id);
            $array_pesanan[] = $pesanan;
        }

        return $array_pesanan;
    }

    public function addPesanan(int $id_pelanggan, int $id_status, int $nombor_meja, string $tarikh, string $cara): void {
        $this->Database->executeQuery(
            "INSERT INTO pesanan (id_pelanggan, id_status, no_meja, tarikh, cara) VALUES (?, ?, ?, ?, ?)",
            "iiiss",
            [$id_pelanggan, $id_status, $nombor_meja, $tarikh, $cara]
        );
    }

    public function getLastInsertedIDOfPesanan(): int {
        return $this->Database->readLastInsertedID();
    }
}
