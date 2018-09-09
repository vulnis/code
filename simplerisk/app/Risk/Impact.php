<?php

namespace App\Risk;

use Illuminate\Database\Eloquent\Model;

class Impact extends Model
{
    protected $table = 'impact';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}