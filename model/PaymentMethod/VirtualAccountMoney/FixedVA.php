<?php  
include_once "VA.php";  

class FixedVA extends VA {
    public function __construct($nomorVA) {
        parent::__construct($nomorVA);
        $this->penggunaan = "Sekali pakai";
    }

    public function getBatasWaktu() {
        return "Tidak ada batas waktu untuk Fixed VA.";
    }
}