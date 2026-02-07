<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MagangExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                $item->id,
                $item->user->name ?? 'N/A',
                $item->nama_lengkap,
                $item->universitas,
                $item->jurusan,
                $item->email,
                $item->no_whatsapp,
                $item->durasi_magang_hari,
                $item->status,
                $item->created_at?->format('Y-m-d H:i:s'),
                $item->updated_at?->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Pengguna',
            'Nama Lengkap',
            'Universitas',
            'Jurusan',
            'Email',
            'No. WhatsApp',
            'Durasi Magang (Hari)',
            'Status',
            'Dibuat',
            'Diupdate',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 15,
            'F' => 20,
            'G' => 15,
            'H' => 18,
            'I' => 12,
            'J' => 20,
            'K' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'A855F7']]],
        ];
    }
}
