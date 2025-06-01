<?php
// controllers/DepartmentController.php

require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../helpers/response.php';

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
        jsonResponse($departments, 'Departments fetched successfully', 200);
    }

    public function store($data, $user)
    {
        if ($user['role'] !== 'admin') {
            jsonResponse(null, 'Forbidden', 403, 'User does not have permission to create department');
            return;
        }

        if (empty($data['name'])) {
            jsonResponse(null, 'Department name is required', 400, 'Missing department name');
            return;
        }

        $this->department->create($data['name']);
        jsonResponse(null, 'Department created successfully', 201);
    }

    public function update($id, $data, $user)
    {
        if ($user['role'] !== 'admin') {
            jsonResponse(null, 'Forbidden', 403, 'User does not have permission to update department');
            return;
        }

        if (empty($data['name'])) {
            jsonResponse(null, 'Department name is required', 400, 'Missing department name');
            return;
        }

        $this->department->update($id, $data['name']);
        jsonResponse(null, 'Department updated successfully', 200);
    }

    public function delete($id, $user)
    {
        if ($user['role'] !== 'admin') {
            jsonResponse(null, 'Forbidden', 403, 'User does not have permission to delete department');
            return;
        }

        $this->department->delete($id);
        jsonResponse(null, 'Department deleted successfully', 200);
    }
}
