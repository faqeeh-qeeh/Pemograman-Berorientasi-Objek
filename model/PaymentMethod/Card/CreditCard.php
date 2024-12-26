<?php  
include_once "BankCard.php";  

class CreditCard implements BankCard {  
    public function doTransaction(int $total): void {  
        echo $total . "Transaction : ";  
    }  
}  
?>