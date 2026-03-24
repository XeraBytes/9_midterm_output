<?php
// config/Database.php

class Database {
    private string $host     = 'localhost';
    private string $port     = '5432';
    private string $dbname   = 'hospital_db';
    private string $user     = 'postgres';
    private string $password = 'admin123'; // change to your actual password

    private ?PDO $conn = null;

    public function connect(): PDO {
        if ($this->conn === null) {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            try {
                $this->conn = new PDO($dsn, $this->user, $this->password, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['error' => 'DB connection error: ' . $e->getMessage()]);
                exit;
            }
        }
        return $this->conn;
    }
}
