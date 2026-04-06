<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = ['event_id', 'name', 'description'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function committeeMembers()
    {
        return $this->hasMany(CommitteeMember::class);
    }
}
