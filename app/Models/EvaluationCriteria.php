<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model
{
    protected $table = 'evaluation_criterias';
    protected $fillable = ['name', 'weight'];

    public function evaluationDetails()
    {
        return $this->hasMany(EvaluationDetail::class, 'criteria_id');
    }
}
