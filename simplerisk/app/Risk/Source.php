<?php

namespace App\Risk;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = 'source';
    protected $primaryKey = 'value';
    public $incrementing = false;
    public $timestamps = false;
}