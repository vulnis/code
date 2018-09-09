<?php

namespace App\Risk;

use Illuminate\Database\Eloquent\Model;

class Probability extends Model
{
    protected $table = 'likelihood';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}