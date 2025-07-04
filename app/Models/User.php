<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'role',
        'avatar',
    ];

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_user', 'user_id', 'class_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(Assignments::class, 'teacher_id');
    }

    public function studentAssignments()
    {
        return $this->hasMany(StudentAssignments::class, 'user_id');
    }

    public function completedAssignments()
    {
        return $this->belongsToMany(Assignments::class, 'student_assignments', 'user_id', 'assignment_id')
            ->withPivot(['status', 'grade', 'feedback', 'student_answer', 'file_path'])
            ->wherePivot('status', 'graded');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function getAvailableAssignmentsAttribute(): Collection
    {
        return Assignments::where('teacher_id', $this->id)
            ->orWhereIn('class_id', $this->classes->pluck('id'))
            ->orderBy('due_date', 'desc')
            ->get();
    }

    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];
}
