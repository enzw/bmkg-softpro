<?php

namespace App\Models;

use App\Enums\Status;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananData extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'layanan_datas';

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
