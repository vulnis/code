<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Severity extends Model
{
    protected $table = 'impact';
    protected $primaryKey = 'value';
    public $timestamps = false;
}