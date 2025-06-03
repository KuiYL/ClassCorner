<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['assignment_id', 'file_name', 'file_path'];
}
