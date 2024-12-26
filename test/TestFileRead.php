<?php  

require_once "../model/Produk.php";  

$database = new Database(); 
$produk = new Produk($database);  

try {  
    $filePath = "controller/fileController/file.txt";
    $data = $produk->readFromFile($filePath);  
    echo $data; 
} catch (FileNotFoundException $e) {  
    echo $e->errorMessage();
}