-- data/10_master.sql
-- Master data awal: Pengguna & Katalog Produk

INSERT INTO users (id, name, email, password, phone, address) VALUES
(1, 'Budi Santoso', 'budi@example.com', '482c811da5d5b4bc6d497ffa98491e38', '08123456789', 'Jl. Sudirman No 10, Jakarta'),
(2, 'Siti Rahma', 'siti@example.com', '482c811da5d5b4bc6d497ffa98491e38', '+628198765432', 'Jl. Thamrin No 5, Surabaya'),
(3, 'Andi Wijaya', 'andi@example.com', '482c811da5d5b4bc6d497ffa98491e38', '62-811-2233-44', NULL), -- Alamat NULL untuk T01
(4, 'Dewi Lestari', 'dewi@example.com', '482c811da5d5b4bc6d497ffa98491e38', '0857-1122-3344', 'Jl. Malioboro No 2, Yogyakarta'),
(5, 'Rizky Pratama', 'rizky@example.com', '482c811da5d5b4bc6d497ffa98491e38', '+62 813 9988 7766', 'Jl. Gatot Subroto No 88, Medan');

INSERT INTO products (id, name, sku, price, stock) VALUES
(102, 'MacBook Air M2', 'MBA-M2-256', 17000000.00, 50),
(103, 'AirPods Pro Gen 2', 'APP-GEN2', 3500000.00, 100),
(104, 'iPad Air 5th Gen', 'IPAD-AIR-5', 9500000.00, 30),
(105, 'Apple Watch Series 9', 'AW-SERIES-9', 6500000.00, 40);
