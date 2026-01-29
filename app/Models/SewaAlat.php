<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SewaAlat extends Model
{
    use HasFactory;
    
    protected $table = 'sewa_alats';
    
    protected $guarded = ['id'];

    protected $fillable = [
        'alat_id',
        'user_id',
        'sewa_mulai',
        'sewa_berakhir',
        'banyak_unit',
        'surat_permohonan',
        'keterangan',
        'status',
        'expedisi',
        'resi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}
