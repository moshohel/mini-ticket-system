<?php
require_once __DIR__ . '/config/database.php';

function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

$db = new Database();
$pdo = $db->connect();

$pdo->exec("SET FOREIGN_KEY_CHECKS=0");
$pdo->exec("TRUNCATE TABLE ticket_notes");
$pdo->exec("TRUNCATE TABLE tickets");
$pdo->exec("TRUNCATE TABLE departments");
$pdo->exec("TRUNCATE TABLE users");
$pdo->exec("SET FOREIGN_KEY_CHECKS=1");

// 1. Insert Users (2 Admins, 2 Agents)
$users = [
    ['Admin One', 'admin@example.com', 'secret', 'admin'],
    ['Admin Two', 'adminTwo@example.com', 'secret', 'admin'],
    ['Agent One', 'agentUser@example.com', 'secret', 'agent'],
    ['Agent Two', 'agentUserTwo@example.com', 'secret', 'agent'],
];

$userIds = [];

foreach ($users as $user) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user[0], $user[1], hashPassword($user[2]), $user[3]]);
    $userIds[] = $pdo->lastInsertId();
}

// 2. Insert Departments
$departments = ['Support', 'Technical', 'Sales'];
$departmentIds = [];

foreach ($departments as $name) {
    $stmt = $pdo->prepare("INSERT INTO departments (name) VALUES (?)");
    $stmt->execute([$name]);
    $departmentIds[] = $pdo->lastInsertId();
}

// 3. Insert Tickets (2 per agent)
$tickets = [
    ['Login not working', 'User cannot log in.', 'open', $userIds[2], $departmentIds[0], null], // Agent One
    ['Page crashes', 'Dashboard crashes after login.', 'in_progress', $userIds[2], $departmentIds[1], $userIds[0]],

    ['Email not sending', 'Emails fail to send.', 'open', $userIds[3], $departmentIds[0], $userIds[1]], // Agent Two
    ['Unable to reset password', 'Reset password email not received.', 'closed', $userIds[3], $departmentIds[1], $userIds[0]],
];

$ticketIds = [];

foreach ($tickets as $ticket) {
    $stmt = $pdo->prepare("INSERT INTO tickets (title, description, status, user_id, department_id, assigned_to) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$ticket[0], $ticket[1], $ticket[2], $ticket[3], $ticket[4], $ticket[5]]);
    $ticketIds[] = $pdo->lastInsertId();
}

// 4. Insert Ticket Notes (2 per ticket)
foreach ($ticketIds as $index => $ticketId) {
    $stmt = $pdo->prepare("INSERT INTO ticket_notes (ticket_id, user_id, note) VALUES (?, ?, ?)");
    $stmt->execute([$ticketId, $userIds[0], "Initial response from admin"]);
    $stmt->execute([$ticketId, $userIds[2 + ($index % 2)], "Agent follow-up note"]);
}

echo "Data seeded successfully!\n";
