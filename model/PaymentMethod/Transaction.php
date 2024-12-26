<?php  
include_once "VA.php";
class Transaction {  
    private $va;  
    private $total;  

    public function __construct($va, $total) {  
        $this->va = $va; // Menggunakan aggregation  
        $this->total = $total;  
    }  

    public function getTransactionDetails() {  
        return [  
            'nomorVA' => $this->va->getNomorVA(),  
            'penggunaan' => $this->va->getPenggunaan(),  
            'batasWaktu' => method_exists($this->va, 'getBatasWaktu') ? $this->va->getBatasWaktu() : 'N/A',  
            'total' => $this->total  
        ];  
    }  
}  
?>