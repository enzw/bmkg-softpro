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
        'lokasi',
        'latitude',
        'longitude',
        'kejadian',
        'no_whatsapp',
        'status',
        'surat_permohonan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
