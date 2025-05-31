<?php
// models/Department.php

require_once __DIR__ . '/../config/database.php';
// models/Department.php

class Department
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll()
    {
        $stmt = $this->conn->query("SELECT * FROM departments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name)
    {
        $stmt = $this->conn->prepare("INSERT INTO departments (name) VALUES (:name)");
        return $stmt->execute(['name' => $name]);
    }

    public function update($id, $name)
    {
        $stmt = $this->conn->prepare("UPDATE departments SET name = :name WHERE id = :id");
        return $stmt->execute(['name' => $name, 'id' => $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM departments WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
