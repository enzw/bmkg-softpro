<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatlog extends Model
{
    protected $fillable = ['question', 
    'answer',
    'session_id',
    'user_name',
    'intent'];
}

