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

    public function updateTables() {  
        // Mendefinisikan query untuk memperbarui tabel  
        $queries = [  
            // Contoh penambahan kolom baru pada tabel produk  
            "ALTER TABLE produk ADD COLUMN IF NOT EXISTS kode_produk VARCHAR(100) UNIQUE AFTER id;",  
            
            // Contoh perubahan tipe data kolom harga_jual  
            "ALTER TABLE produk MODIFY COLUMN harga_jual DECIMAL(12, 2) NOT NULL;",  
            
            // Contoh penambahan kolom pada tabel produk_digital  
            "ALTER TABLE produk_digital ADD COLUMN IF NOT EXISTS tanggal_rilis DATE AFTER kapasitas;",  
            
            // Tambahkan lagi perintah ALTER TABLE sesuai kebutuhan Anda  
        ];  
    
        foreach ($queries as $query) {  
            try {  
                // Menjalankan setiap query  
                $this->connection->exec($query);  
                error_log("Update executed successfully: " . $query);  
            } catch (PDOException $e) {  
                error_log("Error updating table: " . $e->getMessage());  
            }  
        }  
    }  
}
