<?php

namespace App\Risk;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stages';
    public function risk()
    {
        return $this->belongsTo(Risk::class);
    }
}