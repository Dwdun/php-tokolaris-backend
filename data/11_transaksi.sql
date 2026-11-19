-- data/11_transaksi.sql
-- Data transaksi historis untuk menguji masalah N+1 Query pada T06

INSERT INTO orders (id, user_id, total_amount, status, payment_method, created_at) VALUES
(1, 1, 15000000.00, 'PAID', 'bank_transfer', '2024-01-10 10:00:00'),
(2, 2, 17000000.00, 'PAID', 'bank_transfer', '2024-01-11 11:30:00'),
(3, 1, 3500000.00, 'DELIVERED', 'credit_card', '2024-01-12 14:15:00'),
(4, 3, 15000000.00, 'PAID', 'bank_transfer', '2024-02-01 09:00:00'),
(5, 2, 3500000.00, 'PENDING', 'bank_transfer', '2024-02-05 16:45:00'),
(6, 4, 9500000.00, 'PAID', 'bank_transfer', '2024-02-10 08:30:00'),
(7, 5, 6500000.00, 'PAID', 'credit_card', '2024-02-15 13:00:00'),
(8, 1, 17000000.00, 'PAID', 'bank_transfer', '2024-02-18 15:20:00'),
(9, 2, 9500000.00, 'DELIVERED', 'bank_transfer', '2024-02-20 17:10:00'),
(10, 4, 3500000.00, 'PAID', 'bank_transfer', '2024-02-25 11:00:00');

INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 102, 1, 15000000.00),
(2, 102, 1, 17000000.00),
(3, 103, 1, 3500000.00),
(4, 102, 1, 15000000.00),
(5, 103, 1, 3500000.00),
(6, 104, 1, 9500000.00),
(7, 105, 1, 6500000.00),
(8, 102, 1, 17000000.00),
(9, 104, 1, 9500000.00),
(10, 103, 1, 3500000.00);

INSERT INTO invoices (id, order_id, user_id, invoice_number, file_path) VALUES
(1001, 1, 1, 'INV-202401-0001', '/invoices/INV-0001.pdf'),
(1002, 2, 2, 'INV-202401-0002', '/invoices/INV-0002.pdf'),
(1003, 3, 1, 'INV-202401-0003', '/invoices/INV-0003.pdf');
