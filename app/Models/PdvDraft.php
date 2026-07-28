<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdvDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'terminal_id',
        'payload',
        'ip'
    ];
}
