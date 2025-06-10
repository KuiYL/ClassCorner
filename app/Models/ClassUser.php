<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassUser extends Pivot
{
    protected $fillable = ['user_id', 'class_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }
}
