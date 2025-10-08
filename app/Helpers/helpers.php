<?php

// Helper to format rupiah with dot thousand separators
if (!function_exists('formatRupiah')) {
    function formatRupiah($value)
    {
        $digits = preg_replace('/\D/', '', (string) $value);
        $num = $digits === '' ? 0 : intval($digits);
        return 'Rp' . number_format($num, 0, ',', '.');
    }
}
