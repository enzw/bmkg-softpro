<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magang extends Model
{
    use HasFactory;

    protected $table = 'magangs';
    
    protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'jenis_layanan',
        'nama_lengkap',
        'no_whatsapp',
        'email',
        'keterangan',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
