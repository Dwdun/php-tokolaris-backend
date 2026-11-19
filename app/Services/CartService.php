<?php

namespace App\Services;

class CartService {

    /**
     * TKT-02: Kalkulasi Diskon & Persentase Penghematan
     * ❌ BUG: Division by Zero saat diskon voucher bernilai 100% (harga setelah diskon = 0)!
     */
    public function calculateTotal(array $items, ?string $voucherCode = null): array {
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $discount = 0;
        if ($voucherCode === 'GRATIS100') {
            $discount = $subtotal; // Diskon 100%
        } elseif ($voucherCode === 'DISKON10') {
            $discount = $subtotal * 0.10;
        }

        $finalTotal = $subtotal - $discount;

        // ❌ BUG TKT-02: Jika finalTotal == 0, rumus ini memicu DivisionByZeroError di PHP 8!
        $savingsRatio = round(($discount / $finalTotal) * 100, 2);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'final_total' => $finalTotal,
            'savings_ratio' => $savingsRatio
        ];
    }
}
