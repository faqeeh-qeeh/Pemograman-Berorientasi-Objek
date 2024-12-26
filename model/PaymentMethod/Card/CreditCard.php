<?php  
include_once "BankCard.php";  

class CreditCard implements BankCard {  
    public function doTransaction(int $total): void {  
        echo $total . " Transaction using Credit Card.";  // Menampilkan transaksi menggunakan kartu kredit  
    }  
}  
?>