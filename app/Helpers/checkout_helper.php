<?php

if (!function_exists('hitung_biaya_jasa')) {
    function hitung_biaya_jasa($total_harga)
    {
        $total_harga = (float) $total_harga;

        if ($total_harga <= 10000000) {
            return $total_harga * 0.01;
        }

        return $total_harga * 0.02;
    }
}

if (!function_exists('hitung_diskon_voucher')) {
    function hitung_diskon_voucher($total_harga, $voucher_code)
    {
        $total_harga = (float) $total_harga;

        $vouchers = [
            'PROMO2025'  => 0.10,
            'PROMO2026'  => 0.15,
            'AKHIRTAHUN' => 0.25,
        ];

        $voucher_code = strtoupper(trim((string) $voucher_code));

        if ($voucher_code === '' || !isset($vouchers[$voucher_code])) {
            return 0;
        }

        return $total_harga * $vouchers[$voucher_code];
    }
}

if (!function_exists('hitung_free_mouse')) {
    function hitung_free_mouse($total_harga)
    {
        $total_harga = (float) $total_harga;

        if ($total_harga > 15000000) {
            return 150000;
        }

        return 0;
    }
}