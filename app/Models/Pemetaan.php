<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemetaan extends Model
{
    use HasFactory;

    protected $table = 'pemetaans';
    
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'perusahaan',
        'tanggal',
        'lokasi',
        'latitude',
        'longitude',
        'kejadian',
        'status',
        'surat_permohonan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
