-- data/12_cacat_stok.sql
-- Inisialisasi item promosi Flash Sale dengan stok terbatas untuk membuktikan race condition (T04)

INSERT INTO products (id, name, sku, price, stock) VALUES
(101, 'iPhone 15 Pro Promo Flash Sale', 'IPHONE-15-FS', 15000000.00, 10)
ON DUPLICATE KEY UPDATE stock = 10;
