<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asuransi extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'asuransis';
    
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'nama_user',
        'tanggal',
        'lokasi',
        'latitude',
        'longitude',
        'perusahaan',
        'kejadian',
        'no_whatsapp',
        'status',
        'surat_permohonan',
        'ktp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
