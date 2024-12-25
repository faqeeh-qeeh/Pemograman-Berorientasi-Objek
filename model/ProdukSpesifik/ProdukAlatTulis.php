<?php
require_once "../model/Produk.php";

class ProdukAlatTulis extends Produk {
    public $kategori = "Alat Tulis";

    public function getKategori(): string {
        return $this->kategori;
    }
}
