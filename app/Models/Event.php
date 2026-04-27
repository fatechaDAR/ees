<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'status'];

    public function divisions()
    {
        return $this->hasMany(Division::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
