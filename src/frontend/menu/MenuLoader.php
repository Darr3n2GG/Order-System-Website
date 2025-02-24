<?php
class MenuLoader {
    public static function createArrayKategoriMenuItem($array_kategori): array {
        $array_kategori_item = [];
        foreach ($array_kategori as $kategori) {
            $label = $kategori["label"];
            $nama = $kategori["nama"];
            $kategori_item = "<sl-menu-item value='$label'>$nama</sl-menu-item>";
            array_push($array_kategori_item, $kategori_item);
        }
        return $array_kategori_item;
    }

    public static function displayKategoriDanItem(array $array_kategori, array &$array_makanan): void {
        foreach ($array_kategori as $kategori) {
            $label = $kategori["label"];
            $nama = $kategori["nama"];
            $array_makanan_dlm_kategori = self::huntArrayMakananDlmKategori($array_makanan, $nama);
            $array_makanan_item = self::createArrayMakananItem($array_makanan_dlm_kategori);
            echo "<div class='kategori' id='$label'><h1>$nama</h1>";
            foreach ($array_makanan_item as $makanan) {
                echo $makanan;
            }
            echo "</div>";
        }
    }

    private static function huntArrayMakananDlmKategori(array &$array_makanan, string $kategori_nama): array {
        $array_makanan_dlm_kategori = [];
        foreach ($array_makanan as $makanan) {
            if ($makanan["kategori_nama"] == $kategori_nama) {
                array_push($array_makanan_dlm_kategori, $makanan);
                $key = array_search($makanan, $array_makanan);
                array_splice($array_makanan, $key, 1);
            }
        }
        return $array_makanan_dlm_kategori;
    }

    private static function createArrayMakananItem(array $array_makanan): array {
        $array_makanan_item = [];
        foreach ($array_makanan as $makanan) {
            $gambar = $makanan["gambar"];
            $nama = $makanan["nama"];
            $id = $makanan["id"];
            $label = $makanan["label"] . $id;
            $harga = $makanan["harga"];
            $makanan_item = <<<ITEM
            <div class='food_item' id='$id'>
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
