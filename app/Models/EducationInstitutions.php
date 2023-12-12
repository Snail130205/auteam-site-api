<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationInstitutions extends Model
{
    use HasFactory;

    protected $fillable = ['fullName', 'position', 'educationInstitutionName', 'educationTypeId'];
}
