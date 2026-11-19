<?php

namespace App\Controllers;

use App\Support\Database;

class SearchController {

    /**
     * TKT-17: Pencarian Produk
     * ❌ BUG FATAL: SQL Injection via String Concatenation!
     */
    public function search(string $keyword): array {
        // Rentan SQL Injection: $keyword langsung digabungkan ke raw query!
        $sql = "SELECT id, name, sku, price, stock FROM products WHERE name LIKE '%" . $keyword . "%'";
        return Database::query($sql);
    }
}
