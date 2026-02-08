<?php

namespace App\Exports;

use Carbon\Carbon;
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
            $mulai = Carbon::parse($item->tanggal_mulai);
            $selesai = Carbon::parse($item->tanggal_selesai);
            $durasi = $selesai->diffInDays($mulai);
            
            return [
                $item->id,
                $item->user->name ?? 'N/A',
                $item->nama_lengkap,
                $item->universitas,
                $item->fakultas,
                $item->prodi,
                $item->email,
                $item->no_whatsapp,
                $mulai->format('Y-m-d'),
                $selesai->format('Y-m-d'),
                $durasi . ' hari',
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
            'Fakultas',
            'Program Studi',
            'Email',
            'No. WhatsApp',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Durasi (Hari)',
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
            'F' => 15,
            'G' => 20,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 12,
            'L' => 12,
            'M' => 20,
            'N' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'A855F7']]],
        ];
    }
}
