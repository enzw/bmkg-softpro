<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JasaKonsultasiExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
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
                $item->keterangan,
                $item->email,
                $item->no_whatsapp,
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
            'Deskripsi/Keterangan',
            'Email',
            'No. WhatsApp',
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
            'D' => 25,
            'E' => 20,
            'F' => 15,
            'G' => 12,
            'H' => 20,
            'I' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EC4899']]],
        ];
    }
}
