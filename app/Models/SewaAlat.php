<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SewaAlat extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'alat_id',
        'user_id',
        'nama',
        'no_whatsapp',
        'sewa_mulai',
        'sewa_berakhir',
        'banyak_unit',
        'surat_permohonan',
        'keterangan',
        'status',
        'expedisi',
        'resi',
        'ktp',
    ];

    protected $casts = [
        'sewa_mulai' => 'date',
        'sewa_berakhir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rating()
    {
        return $this->morphOne(ServiceRating::class, 'rateable');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}
