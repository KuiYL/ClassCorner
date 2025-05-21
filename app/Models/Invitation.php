<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = ['class_id', 'inviter_id', 'invitee_email', 'status'];

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }
}
