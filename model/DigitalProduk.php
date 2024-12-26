<?php  

require_once "Produk.php";   

class DigitalProduk extends Produk {  
    public $fileSize;

    public function __construct($database, $fileSize) {  
        parent::__construct($database);  
        $this->fileSize = $fileSize;  
    }  

    public function create(): bool {  
        $query = "INSERT INTO {$this->tableName} (nama_produk, harga_jual, harga_beli, stok, deskripsi, fileSize) VALUES (:nama_produk, :harga_jual, :harga_beli, :stok, :deskripsi, :fileSize)";  
        $statement = $this->connection->prepare($query);  

        $statement->bindParam(":nama_produk", $this->nama_produk);  
        $statement->bindParam(":harga_jual", $this->harga_jual);  
        $statement->bindParam(":harga_beli", $this->harga_beli);  
        $statement->bindParam(":stok", $this->stok);  
        $statement->bindParam(":deskripsi", $this->deskripsi);  
        $statement->bindParam(":fileSize", $this->fileSize);  

        return $statement->execute();  
    }  

}