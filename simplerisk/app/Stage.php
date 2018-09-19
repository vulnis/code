<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stages';
    public function risks()
    {
        return $this->belongsTo(Risk::class);
    }
}