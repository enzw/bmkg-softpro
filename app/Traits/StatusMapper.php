<?php

namespace App\Traits;

trait StatusMapper
{
    /**
     * Map database status to display status for Sewa Alat
     * Database status: Belum Lunas, Siap Diambil, Dibawa, Dikembalikan
     * Display status: Menunggu, Diproses, Diproses, Selesai
     */
    public static function mapSewaAlatStatus($dbStatus)
    {
        $mapping = [
            'Belum Lunas' => 'Menunggu',
            'Siap Diambil' => 'Diproses',
            'Dibawa' => 'Diproses',
            'Dikembalikan' => 'Selesai',
        ];

        return $mapping[$dbStatus] ?? $dbStatus;
    }

    /**
     * Get all database status values that map to a display status
     */
    public static function getSewaAlatDbStatusesForDisplay($displayStatus)
    {
        $mapping = [
            'Menunggu' => ['Belum Lunas'],
            'Diproses' => ['Siap Diambil', 'Dibawa'],
            'Selesai' => ['Dikembalikan'],
        ];

        return $mapping[$displayStatus] ?? [];
    }
}
