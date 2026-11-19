-- db/02_index.sql
-- Index bawaan awal: Sengaja TANPA index pada foreign key `user_id` dan `order_id`
-- Ini memicu masalah table scan pada T05.

-- Hanya index primary key yang ada secara default di MySQL InnoDB.
-- Indeks tambahan yang benar akan ditambahkan oleh peserta pada tiket T05:
-- CREATE INDEX idx_orders_user_id ON orders(user_id);
-- CREATE INDEX idx_order_items_order_id ON order_items(order_id);
