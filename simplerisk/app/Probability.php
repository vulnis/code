<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Probability extends Model
{
    protected $table = 'likelihood';
    protected $primaryKey = 'value';
    public $timestamps = false;
}