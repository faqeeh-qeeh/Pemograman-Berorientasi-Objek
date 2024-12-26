<?php  
include_once "BankCard.php";  

class DebitCard implements BankCard {  
    public function doTransaction(int $total): void {  
        echo $total . " Transaction using Debit Card.";  // Menampilkan transaksi menggunakan kartu debit  
    }  
}  
?>