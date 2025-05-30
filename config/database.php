<?php

class Database
{
    private $host = "localhost";
    private $db_name = "mini_ticket_system";
    private $username = "root";
    private $password = ""; // default XAMPP MySQL has no password
    public $conn;

    public function connect()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name;charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'error' => 'Connection failed: ' . $e->getMessage()
            ]);
            exit;
        }
    }
}
