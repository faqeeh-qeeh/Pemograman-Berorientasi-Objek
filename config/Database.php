<?php  
class Database {  
    private $host = "localhost";  
    private $databaseName = "FotoCopy_PBO";  
    private $userName = "root";  
    private $password = "";  
    private $connection;  

    public function getConnection() {  
        if ($this->connection === null) {  
            try {  
                $this->connection = new PDO(  
                    "mysql:host={$this->host};dbname={$this->databaseName}",  
                    $this->userName,  
                    $this->password  
                );  
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
                $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  
            } catch (PDOException $exception) {  
                error_log("Database Connection Error: " . $exception->getMessage());  
                throw new Exception("Database connection failed");  
            }  
        }  
        return $this->connection;  
    }  

    public function createTables() {  
        $query = "  
            CREATE TABLE IF NOT EXISTS produk (  
                id INT AUTO_INCREMENT PRIMARY KEY,  
                nama_produk VARCHAR(255) NOT NULL,  
                harga_jual DECIMAL(10, 2) NOT NULL,  
                harga_beli DECIMAL(10, 2) NOT NULL,  
                stok INT NOT NULL,  
                deskripsi TEXT,  
                kategori ENUM('Alat Tulis', 'Jasa', 'Digital') NOT NULL  
            );  

            CREATE TABLE IF NOT EXISTS produk_alat_tulis (  
                id INT AUTO_INCREMENT PRIMARY KEY,  
                produk_id INT NOT NULL,  
                spesifikasi TEXT,  
                FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE  
            );  

            CREATE TABLE IF NOT EXISTS produk_jasa (  
                id INT AUTO_INCREMENT PRIMARY KEY,  
                produk_id INT NOT NULL,  
                detail_jasa TEXT,  
                FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE  
            );  

            CREATE TABLE IF NOT EXISTS produk_digital (  
                id INT AUTO_INCREMENT PRIMARY KEY,  
                produk_id INT NOT NULL,  
                format_file VARCHAR(100),  
                kapasitas INT,  
                FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE  
            );  
        ";  

        try {  
            $this->connection->exec($query);  
            error_log("Tables created or already exist.");  
        } catch (PDOException $e) {  
            error_log("Table Creation Error: " . $e->getMessage());  
        }  
    }  

    public function updateTables($id, $data) {  
        $query = "UPDATE produk SET   
                    nama_produk = :nama_produk,   
                    harga_jual = :harga_jual,   
                    harga_beli = :harga_beli,   
                    stok = :stok,   
                    deskripsi = :deskripsi,   
                    kategori = :kategori   
                  WHERE id = :id";  

        try {  
            $statement = $this->connection->prepare($query);  

            // Bind data
            $statement->bindParam(":id", $id, PDO::PARAM_INT);  
            $statement->bindParam(":nama_produk", $data['nama_produk'], PDO::PARAM_STR);  
            $statement->bindParam(":harga_jual", $data['harga_jual'], PDO::PARAM_STR);  
            $statement->bindParam(":harga_beli", $data['harga_beli'], PDO::PARAM_STR);  
            $statement->bindParam(":stok", $data['stok'], PDO::PARAM_INT);  
            $statement->bindParam(":deskripsi", $data['deskripsi'], PDO::PARAM_STR);  
            $statement->bindParam(":kategori", $data['kategori'], PDO::PARAM_STR);  

            if ($statement->execute()) {  
                return true;  
            }  
        } catch (PDOException $e) {  
            error_log("Update Error: " . $e->getMessage());  
        }  

        return false;  
    }  
}
