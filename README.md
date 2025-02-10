1️⃣ Create Users Table

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);
2️⃣ Create Lost & Found Table

CREATE TABLE lost_found (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    item VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    type ENUM('lost', 'found') NOT NULL
);
✅ Adding Admin Account
sql
Copy
Edit
INSERT INTO users (email, password) VALUES ('admin@admin.com', '$2y$10$K9qW9gF8MNyUjZCqU/jKmOwplTZM/JM6N4m5qlKbRXjS3lOJKXPia');


Admin Panel Credentials: 
Username: admin@admin.com
Password: admin123
