<?php

namespace App\Risk;

use Illuminate\Database\Eloquent\Model;

class Closure extends Model
{
    protected $table = 'closures';
    public function risk()
    {
        return $this->belongsTo(Risk::class);
    }
}
