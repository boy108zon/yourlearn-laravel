<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;
   
    protected $table = 'log_activity';
    
    protected $fillable = [
        'event',
        'description',
        'ip_address',
        'user_id',
    ];
}

