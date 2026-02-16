<?php

namespace App\Enums;

enum SewaStatus: string
{
    case BELUM_LUNAS = 'Belum Lunas';
    case SIAP_DIAMBIL = 'Siap Diambil';
    case DIBAWA = 'Dibawa';
    case DIKEMBALIKAN = 'Dikembalikan';
    case DITOLAK = 'Ditolak';

    public function label(): string
    {
        return $this->value;
    }


    public function color(): string
    {
        return match ($this) {
            self::BELUM_LUNAS => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800/50 text-yellow-700 dark:text-yellow-300',
            self::SIAP_DIAMBIL, self::DIBAWA => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-300',
            self::DIKEMBALIKAN => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800/50 text-green-700 dark:text-green-300',
            self::DITOLAK => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300',
        };
    }
}
