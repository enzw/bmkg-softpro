<?php

namespace App\Models;

use App\Enums\Status;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magang extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

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

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
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
