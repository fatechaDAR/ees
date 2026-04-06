<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = ['event_id', 'evaluator_id', 'evaluatee_id', 'final_score', 'feedback'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function evaluatee()
    {
        return $this->belongsTo(User::class, 'evaluatee_id');
    }

    public function details()
    {
        return $this->hasMany(EvaluationDetail::class, 'evaluation_id');
    }
}
