<?php

namespace App\Enums;

enum Status: string
{
    case MENUNGGU = 'Menunggu';
    case DIPROSES = 'Diproses';
    case SELESAI = 'Selesai';
    case DITOLAK = 'Ditolak';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU => 'Ditinjau',
            self::DIPROSES => 'Diproses',
            self::SELESAI => 'Selesai',
            self::DITOLAK => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::MENUNGGU => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800/50 text-yellow-700 dark:text-yellow-300',
            self::DIPROSES => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300',
            self::SELESAI => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300',
            self::DITOLAK => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300',
        };
    }
}
