<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationDetail extends Model
{
    protected $fillable = ['evaluation_id', 'criteria_id', 'score'];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    public function criteria()
    {
        return $this->belongsTo(EvaluationCriteria::class, 'criteria_id');
    }
}
