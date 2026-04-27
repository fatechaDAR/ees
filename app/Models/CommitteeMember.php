<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    protected $fillable = ['user_id', 'division_id', 'position'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function evaluationsAsEvaluatee()
    {
        return $this->hasMany(Evaluation::class, 'evaluatee_id');
    }
}
