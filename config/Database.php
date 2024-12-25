<?php
class Database {
    private $host = "localhost";
    private $databaseName = "FotoCopy_PBO";
    private $userName = "root";
    private $password = "";
    public $connection;

    public function getConnection() {
        $this->connection = null;
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
        return $this->connection;
    }
}