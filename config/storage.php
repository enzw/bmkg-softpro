<?php

/**
 * R2 Storage Folder Structure
 * 
 * Root: bmkg/ (AWS_BUCKET)
 * 
 * Folder Structure:
 * ├── permohonan/                          // Main folder for all requests
 * │   ├── sewa-alat/                       // Jasa Sewa Alat MKG files
 * │   ├── kunjungan/                       // Permohonan Kunjungan files
 * │   ├── kunjungan-teknis/                // Layanan Kunjungan Teknis files
 * │   ├── survey/                          // Layanan Survey files
 * │   ├── layanan-data/                    // Layanan Data Geofisika files
 * │   ├── jasa-konsultasi/                 // Jasa Konsultasi files
 * │   ├── asuransi/                        // Layanan Klaim Asuransi files
 * │   └── magang/                          // Magang files
 * 
 * File Naming Convention:
 * {uniqid()}_{timestamp}.{extension}
 * Example: 61e4b5f52d1e0_1673456789.pdf
 * 
 * Access Control:
 * - All files stored in R2 (private)
 * - Access via presigned URLs (60 minutes expiration)
 * - Deletion requires admin role
 * - Download requires authentication
 */

return [
    'folders' => [
        'sewa-alat' => 'permohonan/sewa-alat',
        'kunjungan' => 'permohonan/kunjungan',
        'kunjungan-teknis' => 'permohonan/kunjungan-teknis',
        'survey' => 'permohonan/survey',
        'layanan-data' => 'permohonan/layanan-data',
        'jasa-konsultasi' => 'permohonan/jasa-konsultasi',
        'asuransi' => 'permohonan/asuransi',
        'magang' => 'permohonan/magang',
    ],

    'max_file_size' => 2048, // KB
    'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],
    'presigned_url_expiration' => 60, // minutes
];
