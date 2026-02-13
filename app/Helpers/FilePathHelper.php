<?php

namespace App\Helpers;

class FilePathHelper
{
    /**
     * Get the folder path for each service type
     */
    public static function getServiceFolder($serviceType)
    {
        $folderMap = [
            // Single service controllers
            'sewa-alat' => 'permohonan/sewa-alat',
            'kunjungan' => 'permohonan/kunjungan',
            'survey' => 'permohonan/survey',
            'layanan-data' => 'permohonan/layanan-data',
            'jasa-konsultasi' => 'permohonan/jasa-konsultasi',
            'asuransi' => 'permohonan/asuransi',
            
            // Multi-service (Magang) mappings
            'Magang' => 'permohonan/magang',
            'Layanan Kunjungan Teknis' => 'permohonan/kunjungan-teknis',
            'Layanan Klaim Asuransi' => 'permohonan/asuransi',
            'Layanan Data' => 'permohonan/layanan-data',
            'Layanan Survey' => 'permohonan/survey',
            'Layanan Konsultasi' => 'permohonan/jasa-konsultasi',
        ];
        
        return $folderMap[$serviceType] ?? 'permohonan/lainnya';
    }

    /**
     * Get all service folders
     */
    public static function getAllServiceFolders()
    {
        return [
            'permohonan/sewa-alat' => 'Sewa Alat MKG',
            'permohonan/kunjungan' => 'Permohonan Kunjungan',
            'permohonan/kunjungan-teknis' => 'Layanan Kunjungan Teknis',
            'permohonan/survey' => 'Layanan Survey',
            'permohonan/layanan-data' => 'Layanan Data Geofisika',
            'permohonan/jasa-konsultasi' => 'Jasa Konsultasi',
            'permohonan/asuransi' => 'Layanan Klaim Asuransi',
            'permohonan/magang' => 'Magang',
        ];
    }

    /**
     * Get folder display name
     */
    public static function getFolderName($folderPath)
    {
        $folders = self::getAllServiceFolders();
        return $folders[$folderPath] ?? 'Lainnya';
    }

    /**
     * @deprecated Use getServiceFolder() instead
     */
    public static function getDirectoryForService($serviceType)
    {
        return self::getServiceFolder($serviceType);
    }
}
