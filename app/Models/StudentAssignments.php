<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignments extends Model
{
    use HasFactory;

    protected $table = 'student_assignments'; // важно, если модель не соответствует имени таблицы

    protected $fillable = [
        'user_id',
        'assignment_id',
        'status',
        'file_path',
        'grade',
        'feedback',
        'student_answer',
    ];

    protected $casts = [
        'student_answer' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignment()
    {
        return $this->belongsTo(Assignments::class, 'assignment_id');
    }
}
