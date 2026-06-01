<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'status', 'admin_id'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function divisions()
    {
        return $this->hasMany(Division::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
