<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consequence extends Model
{
    public function causes()
    {
        return $this->belongsToMany(Cause::class);
    }
}
