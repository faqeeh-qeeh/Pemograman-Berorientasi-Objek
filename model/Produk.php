<?php  

require_once "BaseProduk.php";   
require_once "Supplier.php";   
require_once "ProdukInterface.php"; 
require_once "SupplierManagementInterface.php"; 
require_once "../exceptions/FileNotFoundException.php";

class Produk extends BaseProduk implements ProdukInterface, SupplierManagementInterface {  
    public $suppliers = [];  

    public function addSupplier(Supplier $supplier): void {  
        $this->suppliers[] = $supplier;  
    }  

    public function getSuppliers(): array {  
        return $this->suppliers;  
    }  

    public function read(): PDOStatement {  
        $query = "SELECT * FROM {$this->tableName} ORDER BY id ASC";  
        $statement = $this->connection->prepare($query);  
        $statement->execute();  
        return $statement;  
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
        }  
    }  

    public function create(): bool {  
        $query = "INSERT INTO {$this->tableName} (nama_produk, harga_jual, harga_beli, stok, deskripsi) VALUES (:nama_produk, :harga_jual, :harga_beli, :stok, :deskripsi)";  
        $statement = $this->connection->prepare($query);  

        $statement->bindParam(":nama_produk", $this->nama_produk);  
        $statement->bindParam(":harga_jual", $this->harga_jual);  
        $statement->bindParam(":harga_beli", $this->harga_beli);  
        $statement->bindParam(":stok", $this->stok);  
        $statement->bindParam(":deskripsi", $this->deskripsi);  

        return $statement->execute();  
    }  

        // Metode baru untuk membaca data dari file  
    public function readFromFile($filePath) {  
        if (!file_exists($filePath)) {  
            throw new FileNotFoundException("File '{$filePath}' tidak ditemukan.");  
        }  
        
        // Jika file ditemukan, lakukan proses baca  
        $data = file_get_contents($filePath);  
        // Lakukan proses lebih lanjut dengan data di sini  
        return $data;  
    }  
    
    
    public function update(): bool {  
        // Pastikan untuk menggunakan UPDATE dan SET  
        $query = "UPDATE {$this->tableName}   
                  SET nama_produk = :nama_produk,  
                      harga_jual = :harga_jual,  
                      harga_beli = :harga_beli,  
                      stok = :stok,  
                      deskripsi = :deskripsi   
                  WHERE id = :id";  
    
        $statement = $this->connection->prepare($query);  
    
        // Binding parameter  
        $statement->bindParam(":nama_produk", $this->nama_produk);  
        $statement->bindParam(":harga_jual", $this->harga_jual);  
        $statement->bindParam(":harga_beli", $this->harga_beli);  
        $statement->bindParam(":stok", $this->stok);  
        $statement->bindParam(":deskripsi", $this->deskripsi);  
        $statement->bindParam(":id", $this->id); // Mengikat id yang sesuai  
    
        // Eksekusi query  
        return $statement->execute();  
    }  


    public function delete(): bool {
        $query = "DELETE FROM {$this->tableName} WHERE id = :id";
    
        $statement = $this->connection->prepare($query);
    
        // Binding parameter
        $statement->bindParam(":id", $this->id);
    
        // Eksekusi query
        return $statement->execute();
    }
    
}