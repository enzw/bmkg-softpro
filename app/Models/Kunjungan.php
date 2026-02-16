<?php

namespace App\Models;

use App\Enums\Status;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'kunjungans';

    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'jenis_kunjungan',
        'nama_instansi',
        'nama_lengkap',
        'no_whatsapp',
        'jumlah_rombongan',
        'rencana_kunjungan',
        'surat_permohonan',
        'status',
        'ktp',
    ];

    protected $casts = [
        'status' => Status::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rating()
    {
        return $this->morphOne(ServiceRating::class, 'rateable');
    }
}
