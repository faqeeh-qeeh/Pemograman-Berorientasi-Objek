<?php
require_once "../model/Produk.php";

class ProdukJasa extends Produk {
    public $kategori = "Jasa";

    public function getKategori(): string {
        return $this->kategori;
    }

    public function hitungHargaJasa(int $jumlah_halaman, int $harga_per_halaman): int {
        return $jumlah_halaman * $harga_per_halaman;
    }
}
