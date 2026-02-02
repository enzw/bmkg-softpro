<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetaSebaran extends Model
{
    use HasFactory;

    protected $table = 'peta_sebarans';
    
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'perusahaan',
        'tanggal',
        'lokasi',
        'latitude',
        'longitude',
        'kejadian',
        'surat_permohonan',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
