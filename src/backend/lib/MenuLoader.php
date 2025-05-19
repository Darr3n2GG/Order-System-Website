<?php

namespace lib;

class MenuLoader {
    private $array_kategori;
    private $array_produk;

    public function __construct(array $array_kategori, array $array_produk) {
        $this->array_kategori = $array_kategori;
        $this->array_produk = $array_produk;
    }

    public function displayKategoriItem(): void {
        $array_kategori_item_html = $this->createArrayItemKategoriHTML();
        foreach ($array_kategori_item_html as $kategori_item_html) {
            echo $kategori_item_html;
        }
    }

    public function createArrayItemKategoriHTML(): array {
        $array_item_kategori = [];
        foreach ($this->array_kategori as $kategori) {
            $item_kategori = self::createItemKategoriHTML($kategori);
            $array_item_kategori[] = $item_kategori;
        }
        return $array_item_kategori;
    }

    public static function createItemKategoriHTML(array $kategori): string {
        $label = $kategori["label"];
        $nama = $kategori["nama"];

        return "<sl-menu-item value='$label'>$nama</sl-menu-item>";
    }

    public function displayKategoriDanProduk(): void {
        foreach ($this->array_kategori as $kategori) {
            $label = $kategori["label"];
            $nama = $kategori["nama"];
            $array_produk_dalam_kategori = $this->createArrayProdukDalamKategori($nama);
            $array_item_produk_html = $this->createArrayItemProdukHTML($array_produk_dalam_kategori);
            echo <<<FRONT
            <div class='kategori_title' id='$label'>
                <h1>$nama</h1>
                <div class='food_item_container'>
            FRONT;

            foreach ($array_item_produk_html as $item_produk_html) {
                echo $item_produk_html;
            }

            echo <<<BACK
                </div>
            </div>
            BACK;
        }
    }

    private function createArrayProdukDalamKategori(string $nama_kategori): array {
        $array_produk_dalam_kategori = [];
        foreach ($this->array_produk as $produk) {
            if ($produk["kategori_nama"] == $nama_kategori) {
                $array_produk_dalam_kategori[] = $produk;
            }
        }
        return $array_produk_dalam_kategori;
    }

    private function huntArrayProdukDalamKategori(string $nama_kategori): array {
        $array_produk_dalam_kategori = [];
        foreach ($this->array_produk as $produk) {
            if ($produk["kategori_nama"] == $nama_kategori) {
                $array_produk_dalam_kategori[] = $produk;
                $key = array_search($produk, $this->array_produk);
                array_splice($this->array_produk, $key, 1);
            }
        }
        return $array_produk_dalam_kategori;
    }

    private function createArrayItemProdukHTML($array_produk): array {
        $array_item_produk = [];
        foreach ($array_produk as $produk) {
            $item_produk = self::createItemProdukHTML($produk);

            $array_item_produk[] = $item_produk;
        }
        return $array_item_produk;
    }

    public static function createItemProdukHTML($produk): string {
        $gambar = $produk["gambar"];
        $nama = $produk["nama"];
        $id = $produk["id"];
        $label = $produk["label"] . $id;
        $harga = $produk["harga"];

        return <<<ITEM
        <div class='food_item' data-id='$id'>
            <img src='$gambar' alt='$nama' onerror="this.onerror=null; this.src='/Order-System-Website/src/assets/produk/placeholder.png';">
            <div class='food_info'>
                <div class='food_row'>
                    <h3>$nama</h3>
                    <sl-tag size='small' pill>$label</sl-tag>
                </div>
                <div class='food_row'>
                    <p><strong>RM $harga</strong></p>
                </div>
            </div>
            <sl-button size="small" circle>
                <sl-icon name="plus-lg"></sl-icon>
            </sl-button>
        </div>
        ITEM;
    }
}
