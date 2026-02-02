<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asuransi extends Model
{
    use HasFactory;

    protected $table = 'asuransis';
    
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'perusahaan',
        'tanggal',
        'jumlah_rombongan',
        'nama_lengkap',
        'nomor_whatsapp',
        'kejadian',
        'status',
        'surat_permohonan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
