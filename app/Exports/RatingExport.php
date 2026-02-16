<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RatingExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
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
                $item->rating,
                $this->getRatingLabel($item->rating),
                class_basename($item->rateable_type),
                $item->review ?? '-',
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
            'Rating',
            'Label Rating',
            'Tipe Layanan',
            'Ulasan',
            'Dibuat',
            'Diupdate',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 25,
            'C' => 10,
            'D' => 15,
            'E' => 20,
            'F' => 40,
            'G' => 20,
            'H' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '366092']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }

    private function getRatingLabel($rating)
    {
        return match($rating) {
            5 => 'Sangat Puas',
            4 => 'Puas',
            3 => 'Cukup Puas',
            2 => 'Kurang Puas',
            1 => 'Sangat Tidak Puas',
            default => 'N/A',
        };
    }
}
