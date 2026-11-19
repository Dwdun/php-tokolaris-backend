<?php

namespace App\Controllers;

use App\Support\Database;

class InvoiceController {

    /**
     * TKT-18: Unduh Invoice
     * ❌ BUG FATAL: Insecure Direct Object Reference (IDOR)!
     * Mengambil file invoice berdasarkan ID tanpa memvalidasi kepemilikan user.
     */
    public function download(int $invoiceId, int $currentUserId): array {
        // Rentan IDOR: Hanya query berdasarkan invoice ID saja!
        $invoice = Database::query("SELECT * FROM invoices WHERE id = ?", [$invoiceId])[0] ?? null;

        if (!$invoice) {
            return ['status' => 'error', 'code' => 404, 'message' => 'Invoice tidak ditemukan'];
        }

        // ❌ BUG: Tidak ada validasi $invoice['user_id'] === $currentUserId
        return [
            'status' => 'success',
            'code' => 200,
            'data' => $invoice
        ];
    }
}
