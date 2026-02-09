<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JasaKonsultasi extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'jasa_konsultasis';
    
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'no_whatsapp',
        'email',
        'keterangan',
        'status',
        'surat_permohonan',
        'ktp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
