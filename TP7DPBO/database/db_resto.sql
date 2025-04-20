

CREATE TABLE menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10, 2) NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(20) NOT NULL UNIQUE, -- Kolom kode order
    user_id INT NOT NULL,
    total_harga DECIMAL(10, 2) NOT NULL,
    nomor_meja INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);


CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_id INT NOT NULL,
    quantity INT NOT NULL,
    harga DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) AS (harga * quantity) STORED,
    FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menus(id)
        ON DELETE CASCADE
);

INSERT INTO menus (nama_menu, deskripsi, harga) VALUES
('Nasi Goreng Spesial', 'Nasi goreng dengan ayam dan telur', 25000),
('Mie Ayam Bakso', 'Mie ayam dengan tambahan bakso sapi', 20000),
('Es Teh Manis', 'Teh manis dingin dengan es batu', 5000),
('Jus Alpukat', 'Jus alpukat segar dengan susu coklat', 15000),
('Ayam Geprek Level 3', 'Ayam geprek pedas dengan sambal level 3', 22000);

INSERT INTO users (nama, email, phone) VALUES
('Andi Saputra', 'andi@mail.com', '081234567890'),
('Budi Santoso', 'budi@mail.com', '082233445566'),
('Citra Lestari', 'citra@mail.com', '083344556677');

INSERT INTO orders (order_code, user_id, total_harga, nomor_meja, created_at) VALUES
('ORD001', 1, 50000, 5, NOW()),
('ORD002', 2, 27000, 3, NOW()),
('ORD003', 3, 47000, 2, NOW());

INSERT INTO order_items (order_id, menu_id, quantity, harga) VALUES
(1, 1, 2, 25000),  
(2, 2, 1, 20000),  
(2, 3, 1, 5000),   
(3, 5, 1, 22000),  
(3, 4, 1, 15000),  
(3, 3, 2, 5000);   