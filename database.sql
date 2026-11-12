CREATE DATABASE IF NOT EXISTS kapehan_db;
USE kapehan_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'customer',
  status VARCHAR(20) NOT NULL DEFAULT 'active'
);

CREATE TABLE menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  category VARCHAR(50) NOT NULL,
  price DECIMAL(8,2) NOT NULL
);

INSERT INTO menu_items (name, category, price) VALUES
('Barako Coffee', 'Hot', 90),
('Iced Spanish Latte', 'Cold', 130),
('Ensaymada', 'Pastry', 75);
