<?php  

require_once "BaseProduk.php";   
require_once "ProdukInterface.php";   

class PhysicalProduct extends BaseProduk implements ProdukInterface {  
    // Menambahkan properti khusus untuk produk fisik  
    public $weight; // berat produk  

    public function __construct($database, $weight) {  
        parent::__construct($database);  
        $this->weight = $weight;  
    }  

    public function read(): PDOStatement {  
        // Bisa mengimplementasikan pengaturan bacaan produk fisik  
        $query = "SELECT * FROM {$this->tableName} WHERE type='physical' ORDER BY id ASC";  
        $statement = $this->connection->prepare($query);  
        $statement->execute();  
        return $statement;  
    }  

    public function create(): bool {  
        // Implementasi pembuatan produk fisik  
        $query = "INSERT INTO {$this->tableName} (nama_produk, harga_jual, harga_beli, stok, deskripsi, weight) VALUES (:nama_produk, :harga_jual, :harga_beli, :stok, :deskripsi, :weight)";  
        $statement = $this->connection->prepare($query);  

        $statement->bindParam(":nama_produk", $this->nama_produk);  
        $statement->bindParam(":harga_jual", $this->harga_jual);  
        $statement->bindParam(":harga_beli", $this->harga_beli);  
        $statement->bindParam(":stok", $this->stok);  
        $statement->bindParam(":deskripsi", $this->deskripsi);  
        $statement->bindParam(":weight", $this->weight);  

        return $statement->execute();  
    }  

    public function readById(): void {  
        $query = "SELECT * FROM {$this->tableName} WHERE id = ?";  
        $statement = $this->connection->prepare($query);  
        $statement->bindParam(1, $this->id);  
        $statement->execute();  
        $row = $statement->fetch(PDO::FETCH_ASSOC);  

        if ($row) {  
            $this->nama_produk = $row["nama_produk"];  
            $this->harga_jual = $row["harga_jual"];  
            $this->harga_beli = $row["harga_beli"];  
            $this->stok = $row["stok"];  
            $this->deskripsi = $row["deskripsi"];  
            $this->weight = $row["weight"];  
        }  
    }  
}