<?php
// controllers/DepartmentController.php

require_once __DIR__ . '/../models/Department.php';

class DepartmentController
{
    private Department $department;

    public function __construct()
    {
        $this->department = new Department();
    }

    public function index()
    {
        $departments = $this->department->getAll();
        echo json_encode($departments);
    }

    public function store($data, $user)
    {
        if ($user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            return;
        }

        if (empty($data['name'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Department name is required']);
            return;
        }

        $this->department->create($data['name']);
        echo json_encode(['message' => 'Department created']);
    }

    public function update($id, $data, $user)
    {
        if ($user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            return;
        }

        if (empty($data['name'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Department name is required']);
            return;
        }

        $this->department->update($id, $data['name']);
        echo json_encode(['message' => 'Department updated']);
    }

    public function delete($id, $user)
    {
        if ($user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            return;
        }

        $this->department->delete($id);
        echo json_encode(['message' => 'Department deleted']);
    }
}
