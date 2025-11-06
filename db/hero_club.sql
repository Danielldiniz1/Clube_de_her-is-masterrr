-- Cria o novo banco de dados
CREATE DATABASE IF NOT EXISTS `hero-club` 
/*!40100 DEFAULT CHARACTER SET utf8mb3 */ 
/*!80016 DEFAULT ENCRYPTION='N' */;

USE `hero-club`;

-- Tabela de tipos de usuários (store, creator)
CREATE TABLE users_types (
  id INT NOT NULL AUTO_INCREMENT,
  description VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

-- Tipos padrão: 1 para store (vendedor), 2 para client (cliente)
INSERT INTO users_types (description) VALUES 
('store'), 
('client');

-- Tabela de usuários
CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  idType INT NOT NULL DEFAULT 2, -- Default para 'client'
  name VARCHAR(255) NOT NULL,
  email VARCHAR(191) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (idType) REFERENCES users_types(id)
);

-- Clubs
CREATE TABLE clubs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    club_name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL
);

-- Products
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    club_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    category_id INT,
    fandom VARCHAR(100),
    rarity ENUM('common', 'rare', 'exclusive') DEFAULT 'common',
    sku VARCHAR(50) UNIQUE,
    is_physical BOOLEAN DEFAULT TRUE,
    subscription_only BOOLEAN DEFAULT FALSE,
    weight_grams INT,
    dimensions_cm VARCHAR(50),
    image_url VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (club_id) REFERENCES clubs(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Coupons
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    club_id INT NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount_percent DECIMAL(5,2),
    discount_amount DECIMAL(10,2),
    valid_until DATETIME,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (club_id) REFERENCES clubs(id)
);

-- Subscriptions
CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    club_id INT NOT NULL,
    start_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'canceled', 'suspended') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (club_id) REFERENCES clubs(id)
);

-- Payments
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subscription_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('paid', 'pending', 'failed') DEFAULT 'pending',
    payment_gateway VARCHAR(50),
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id)
);

-- Reports
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    club_id INT NOT NULL,
    generated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_subscribers INT,
    total_revenue DECIMAL(10,2),
    products_sold INT,
    FOREIGN KEY (club_id) REFERENCES clubs(id)
);

select * from users;

INSERT INTO products (
    club_id,
    name,
    description,
    price,
    stock,
    category_id,
    fandom,
    rarity,
    sku,
    subscription_only,
    weight_grams,
    image_url
) VALUES (
    2,
    'Action Figure Rara',
    'Figura de ação colecionável de 20cm, edição limitada para membros.',
    199.99,
    50,
    3,
    'Super Heróis',
    'rare',
    'FIG-SH-082',
    TRUE,
    350,
    'https://example.com/images/figure.jpg'
);

INSERT INTO clubs (user_id, club_name, description)
VALUES
    (3, 'Gamers Retrô', 'Para quem ama os clássicos dos videogames, dos 8 aos 64 bits.'),
    (1, 'Heróis dos Quadrinhos', 'Clube dedicado à leitura e discussão de HQs da Marvel e DC.'),
    (4, 'Clube do Cinema Asiático', NULL);


-- ... (código existente)
    select * from users;
    -- Migração para adicionar suporte a múltiplas imagens de produtos
-- Execute este script no seu banco de dados
-- 1. Criar tabela para múltiplas imagens de produtos
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 2. Migrar imagens existentes da coluna image_url para a nova tabela
INSERT INTO product_images (product_id, image_path, is_primary, display_order)
SELECT id, image_url, TRUE, 1 
FROM products 
WHERE image_url IS NOT NULL AND image_url != '';

-- 3. Remover campo SKU da tabela products
ALTER TABLE products DROP COLUMN sku;

-- 4. Opcional: Remover coluna image_url após migração (descomente se desejar)
-- ALTER TABLE products DROP COLUMN image_url;

-- Índices para melhor performance
CREATE INDEX idx_product_images_product_id ON product_images(product_id);
CREATE INDEX idx_product_images_primary ON product_images(is_primary);


select * from product_images;

-- Tabela para tokens de recuperação de senha
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(191) NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_email (email)
);

-- Tabela de itens de carrinho por usuário
CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_user_product (user_id, product_id)
);
