<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignments extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'teacher_id',
        'class_id',
        'type',
        'options',
        'status',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function studentAssignments()
    {
        return $this->hasMany(StudentAssignments::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function getStatusNameAttribute()
    {
        $statuses = [
            'pending' => 'В ожидании',
            'active' => 'Активно',
            'completed' => 'Выполнено',
        ];
        return $statuses[$this->status] ?? $this->status;
    }
public function students()
    {
        return $this->belongsToMany(User::class, 'student_assignments', 'assignment_id', 'user_id')
            ->withPivot(['status', 'grade', 'feedback', 'student_answer', 'file_path'])
            ->withTimestamps();
    }

}
