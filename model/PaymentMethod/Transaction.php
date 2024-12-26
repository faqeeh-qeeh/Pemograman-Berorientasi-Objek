<?php  
include_once "BankCard.php";  
include_once "VA.php";  

class Transaction {  
    private $va;  
    private $bankCard;  
    private $total;  

    public function __construct(VA $va, BankCard $bankCard, int $total) {  
        $this->va = $va;  // Menggunakan aggregation  
        $this->bankCard = $bankCard;  // Menggunakan aggregation  
        $this->total = $total;  
    }  

    public function executeTransaction() {  
        // Melakukan transaksi dengan objek BankCard dan VA yang terhubung  
        $this->bankCard->doTransaction($this->total);  
        return [  
            'nomorVA' => $this->va->getNomorVA(),  
            'penggunaan' => $this->va->getPenggunaan(),  
            'total' => $this->total,  
        ];  
    }  
}  
?>