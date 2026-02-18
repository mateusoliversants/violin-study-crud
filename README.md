# 🎻 Violin Study Manager

A full-stack web application for managing violin study sessions and materials.

## Features:

* User authentication (login system)
* Create, edit and delete study sessions
* Link sessions to study materials (apostilas)
* Upload and download PDF files
* Responsive interface with Bootstrap

## Technologies:

* PHP
* MySQL
* Bootstrap
* JavaScript

## Project Evolution:

This project was initially built using **JavaScript and LocalStorage**.

It was later **refactored into a full-stack application**, adding:

* PHP backend
* MySQL database
* File upload system (PDF)
* Relational data (sessions ↔ materials)

## Project Structure:

* `/login-paginas` → authentication
* `/apostilas` → study materials CRUD + upload/download
* `/sessoes` → study sessions CRUD + linking to materials
* `/uploads/apostilas` → stored PDF files

## Notes:

* Only PDF files are allowed for upload

## Setup (Manual Database Creation)

To run this project locally, you must manually create the database and tables.

### 1. Create a MySQL database

```sql
CREATE DATABASE violin_study;
```

---

### 2. Create the tables

#### Users

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Apostilas

```sql
CREATE TABLE apostilas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    nivel VARCHAR(50) NOT NULL,
    arquivo VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Sessoes

```sql
CREATE TABLE sessoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    sessao_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    id_apostila INT NULL,
    conteudo TEXT,
    objetivo TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_apostila) REFERENCES apostilas(id) ON DELETE SET NULL
);
```

---

### 3. Configure database connection

Edit the file:

```
/database.php
```

And set your credentials:

```php
$host = "localhost";
$db   = "violin_study";
$user = "root";
$pass = "";
```

---

### 4. Run the project

Use XAMPP, WAMP or similar and access via browser.



