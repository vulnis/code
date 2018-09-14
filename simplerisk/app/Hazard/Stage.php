<?php

namespace App\Hazard;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stages';
    public function hazard()
    {
        return $this->belongsTo(Hazard::class);
    }
}