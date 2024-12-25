<?php
require_once "../model/Produk.php";

class ProdukDigital extends Produk {
    public $formatFile;
    public $kapasitas;

    public function __construct($database, $formatFile, $kapasitas) {
        parent::__construct($database);
        $this->formatFile = $formatFile;
        $this->kapasitas = $kapasitas;
    }

    public function getDetailDigital(): array {
        return [
            "format" => $this->formatFile,
            "kapasitas" => $this->kapasitas
        ];
    }
}
