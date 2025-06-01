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

## Authentication

- Token-based authentication using a middleware

- Tokens stored in storage/tokens.json

- Include token as a Bearer token in the Authorization header for all protected endpoints.

  Authorization: Bearer <your-token-here>

📚 API Endpoints Overview

Auth Endpoints

| Method | Endpoint         | Description             |
| ------ | ---------------- | ----------------------- |
| POST   | `/auth/register` | Register new user       |
| POST   | `/auth/login`    | Login and receive token |
| POST   | `/auth/logout`   | Logout                  |

Department Endpoints (Admin only)

| Method | Endpoint           | Description       |
| ------ | ------------------ | ----------------- |
| GET    | `/departments`     | List departments  |
| POST   | `/departments`     | Create department |
| PUT    | `/departments?id=` | Update department |
| DELETE | `/departments?id=` | Delete department |

Ticket Endpoints

| Method | Endpoint                      | Description           |
| ------ | ----------------------------- | --------------------- |
| GET    | `/tickets`                    | List all tickets      |
| GET    | `/tickets?id=1`               | Get a ticket by ID    |
| POST   | `/tickets`                    | Create a new ticket   |
| PUT    | `/tickets?action=assign&id=1` | Assign ticket to self |
| PUT    | `/tickets?action=status&id=1` | Update ticket status  |
| DELETE | `/tickets?id=1`               | Delete a ticket       |

Ticket Notes Endpoints

| Method | Endpoint                    | Description             |
| ------ | --------------------------- | ----------------------- |
| GET    | `/ticket-notes?ticket_id=1` | List notes for a ticket |
| POST   | `/ticket-notes`             | Add a note to a ticket  |

## Author

- [Mohammad Shohel]
  https://github.com/moshohel
  mohammadshohel866@gmail.com
