<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLeaders extends Model
{
    use HasFactory;
    protected $fillable = ['fullName', 'phone', 'email', 'create_at'];
}
