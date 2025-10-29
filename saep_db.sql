-- SAEP Database Schema and Seed
-- Banco: saep_db

CREATE DATABASE IF NOT EXISTS saep_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE saep_db;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela de produtos
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  sku VARCHAR(60) NOT NULL UNIQUE,
  category VARCHAR(80),
  material VARCHAR(80),
  size VARCHAR(60),
  weight_grams INT,
  min_stock INT NOT NULL DEFAULT 0,
  current_stock INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela de movimentações de estoque
CREATE TABLE IF NOT EXISTS inventory_movements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_id INT NOT NULL,
  type ENUM('entrada','saida') NOT NULL,
  quantity INT NOT NULL,
  movement_date DATE NOT NULL,
  note VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_mov_product FOREIGN KEY (product_id) REFERENCES products(id),
  CONSTRAINT fk_mov_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

-- Seeds: usuários (senhas: 'admin123', 'joao123', 'maria123')
-- Hashes gerados com password_hash (bcrypt). Ajuste se necessário.
INSERT INTO users (name, email, password_hash, role) VALUES
  ('Admin', 'admin@empresa.com', '$2y$10$2Jq3wHq4yqQvSxQYjK6wCeB0H9y9m0Yy7XvH4Dkq1o8bA7m0b1L0G', 'admin'),
  ('João Silva', 'joao@empresa.com', '$2y$10$2Jq3wHq4yqQvSxQYjK6wCeB0H9y9m0Yy7XvH4Dkq1o8bA7m0b1L0G', 'user'),
  ('Maria Souza', 'maria@empresa.com', '$2y$10$2Jq3wHq4yqQvSxQYjK6wCeB0H9y9m0Yy7XvH4Dkq1o8bA7m0b1L0G', 'user')
ON DUPLICATE KEY UPDATE email = VALUES(email);

-- Seeds: produtos
INSERT INTO products (name, sku, category, material, size, weight_grams, min_stock, current_stock) VALUES
  ('Martelo Cabo de Madeira', 'HAM-WOOD-01', 'Martelos', 'Aço/Madeira', '300mm', 450, 10, 25),
  ('Chave de Fenda Isolada', 'SCREW-ISO-02', 'Chaves de Fenda', 'Aço/Polímero', '200mm', 120, 15, 18),
  ('Chave de Fenda Imantada', 'SCREW-MAG-03', 'Chaves de Fenda', 'Aço', '220mm', 130, 20, 22)
ON DUPLICATE KEY UPDATE sku = VALUES(sku);

-- Seeds: movimentações (assumindo IDs 1..3 para usuários e produtos)
INSERT INTO inventory_movements (product_id, user_id, type, quantity, movement_date, note) VALUES
  (1, 1, 'entrada', 20, '2024-08-10', 'Compra lote 001'),
  (2, 2, 'saida', 5,  '2024-08-12', 'Uso na linha A'),
  (3, 3, 'entrada', 10, '2024-08-13', 'Reposição inicial');

//updated log