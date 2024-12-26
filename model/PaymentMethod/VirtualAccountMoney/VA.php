<?php  
class VA {  
    protected $nomorVA;  
    protected $penggunaan;  

    public function __construct($nomorVA) {  
        $this->nomorVA = $nomorVA;  
    }  

    public function getNomorVA() {  
        return $this->nomorVA;  
    }  

    public function getPenggunaan() {  
        return $this->penggunaan;  
    }  
}  
?>