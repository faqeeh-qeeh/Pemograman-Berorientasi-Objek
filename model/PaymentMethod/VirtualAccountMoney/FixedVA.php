<?php  
include_once "VA.php";  

class DynamicVA extends VA {  
    private $expiredDate;  

    public function __construct($nomorVA, $expiredDate) {  
        parent::__construct($nomorVA);  
        $this->penggunaan = "Sekali pakai";  
        $this->expiredDate = $expiredDate;  
    }  

    public function getBatasWaktu() {  
        return "Batas waktu: " . $this->expiredDate;  
    }  
}  
?>