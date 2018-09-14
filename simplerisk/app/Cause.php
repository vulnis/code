<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cause extends Model
{
    public function consequences()
    {
        return $this->belongsToMany(Consequence::class);
    }
}
