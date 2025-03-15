<?php
class MenuLoader {
    private $array_kategori;
    private $array_makanan;

    public function __construct($array_kategori, $array_makanan) {
        $this->array_kategori = $array_kategori;
        $this->array_makanan = $array_makanan;
    }

    public function displayKategoriItem(): void {
        $array_kategori_item = $this->createArrayKategoriItem();
        foreach ($array_kategori_item as $kategori) {
            echo $kategori;
        }
    }

    public function createArrayKategoriItem(): array {
        $array_kategori_item = [];
        foreach ($this->array_kategori as $kategori) {
            $label = $kategori["label"];
            $nama = $kategori["nama"];
            $kategori_item = "<sl-menu-item value='$label'>$nama</sl-menu-item>";
            array_push($array_kategori_item, $kategori_item);
        }
        return $array_kategori_item;
    }

    public function displayKategoriDanMakanan(): void {
        foreach ($this->array_kategori as $kategori) {
            $label = $kategori["label"];
            $nama = $kategori["nama"];
            $array_makanan_dlm_kategori = $this->createArrayMakananDlmKategori($nama);
            $array_makanan_item = $this->createArrayMakananItem($array_makanan_dlm_kategori);
            echo "<div class='kategori_title' id='$label'><h1>$nama</h1>";
            foreach ($array_makanan_item as $makanan) {
                echo $makanan;
            }
            echo "</div>";
        }
    }

    private function createArrayMakananDlmKategori(string $kategori_nama): array {
        $array_makanan_dlm_kategori = [];
        foreach ($this->array_makanan as $makanan) {
            if ($makanan["kategori_nama"] == $kategori_nama) {
                array_push($array_makanan_dlm_kategori, $makanan);
            }
        }
        return $array_makanan_dlm_kategori;
    }

    private function huntArrayMakananDlmKategori(string $kategori_nama): array {
        $array_makanan_dlm_kategori = [];
        foreach ($this->array_makanan as $makanan) {
            if ($makanan["kategori_nama"] == $kategori_nama) {
                array_push($array_makanan_dlm_kategori, $makanan);
                $key = array_search($makanan, $this->array_makanan);
                array_splice($array_makanan, $key, 1);
            }
        }
        return $array_makanan_dlm_kategori;
    }

    private function createArrayMakananItem($array_makanan): array {
        $array_makanan_item = [];
        foreach ($array_makanan as $makanan) {
            $gambar = $makanan["gambar"];
            $nama = $makanan["nama"];
            $id = $makanan["id"];
            $label = $makanan["label"] . $id;
            $harga = $makanan["harga"];
            $makanan_item = <<<ITEM
            <div class='food_item' data-id='$id'>
                <img src='$gambar' alt='$nama'>
                <div class='food_info'>
                    <div class='food_row'>
                        <h2>$nama</h2>
                        <sl-tag size='small' pill>$label</sl-tag>
                    </div>
                    <div class='food_row'>
                        <p><strong>Harga : RM $harga</strong></p>
                    </div>
                </div>
            </div>
            ITEM;

            array_push($array_makanan_item, $makanan_item);
        }
        return $array_makanan_item;
    }
}
