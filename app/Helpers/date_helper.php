<?php

if (!function_exists('format_indo_date')) {
    function format_indo_date($date)
    {
        if (empty($date) || $date === '0000-00-00') {
            return '-';
        }

        $months = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $time  = strtotime($date);
        $day   = date('j', $time);
        $month = $months[(int)date('n', $time)];
        $year  = date('Y', $time);

        return "{$day} {$month} {$year}";
    }
}
