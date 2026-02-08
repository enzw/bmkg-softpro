<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsuransiExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
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
                $item->nama_user,
                $item->tanggal,
                $item->lokasi,
                $item->perusahaan,
                $item->kejadian,
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
            'Nama',
            'Tanggal Kejadian',
            'Lokasi',
            'Perusahaan',
            'Kejadian',
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
            'D' => 15,
            'E' => 20,
            'F' => 20,
            'G' => 25,
            'H' => 15,
            'I' => 12,
            'J' => 20,
            'K' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E11D48']]],
        ];
    }
}
