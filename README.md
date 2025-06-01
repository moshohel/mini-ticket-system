# Mini Support Ticketing System API

## 📦 Features

- User authentication (Login, Logout)
- Role-based access (Admin & Agent)
- Department CRUD (admin only)
- Ticket CRUD (create, assign, update, delete)
- Ticket Notes (agent/admin communication)
- JSON-formatted API responses
- Simple rate-limiting system
- Postman collection provided for API testing

# 🛠️ Installation Guide

### ⚙️ Requirements

- PHP >= 8.2
- Composer
- MySQL
- Git

---

### 📥 Clone the Repository

Clone the repository

    git clone https://github.com/moshohel/mini-ticket-system.git

Switch to the repo folder

    cd mini-ticket-system

📦 Install Dependencies

Create a MySQL database named:

    CREATE DATABASE mini_ticket_system;

Import the Schema

Import the provided SQL schema (schema.sql file from database folder ) into the mini_ticket_system DB using phpMyAdmin or MySQL CLI.

## Database seeding

Run the database seeder for test Data

    php seed.php

## Configure Apache

Place the project inside htdocs if using XAMPP.

Start Apache and MySQL via XAMPP.

## Visit:

    http://localhost/mini-ticket-system/public/

## Author

- [Mohammad Shohel](https://github.com/moshohel)
