<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magang extends Model
{
    use HasFactory;

    protected $table = 'magangs';
    
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'no_whatsapp',
        'email',
        'universitas',
        'fakultas',
        'prodi',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'surat_permohonan',
        'surat_ijin_magang',
        'kartu_mahasiswa',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
