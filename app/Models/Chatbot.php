<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Chatbot extends Model
{
    use HasUuid;

    protected $table = 'chatbots';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = ['id'];

    public function ratings()
    {
        return $this->morphMany(ServiceRating::class, 'rateable');
    }
}
