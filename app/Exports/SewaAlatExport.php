<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SewaAlatExport implements FromCollection, WithHeadings, WithColumnWidths, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            $sewa_mulai = Carbon::parse($item->sewa_mulai);
            $sewa_berakhir = Carbon::parse($item->sewa_berakhir);
            $durasi = $sewa_berakhir->diffInDays($sewa_mulai);

            return [
                $item->id,
                $item->user->name ?? 'N/A',
                $item->nama,
                $item->alat->nama ?? 'N/A',
                $item->banyak_unit,
                $sewa_mulai->format('Y-m-d'),
                $sewa_berakhir->format('Y-m-d'),
                $durasi . ' hari',
                $item->keterangan,
                $item->status->value,
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
            'Nama (Pemesan)',
            'Nama Alat',
            'Jumlah Unit',
            'Tanggal Mulai',
            'Tanggal Berakhir',
            'Durasi Sewa',
            'Keterangan',
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
            'E' => 12,
            'F' => 15,
            'G' => 15,
            'H' => 12,
            'I' => 25,
            'J' => 12,
            'K' => 20,
            'L' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '22C55E']]],
        ];
    }
}
