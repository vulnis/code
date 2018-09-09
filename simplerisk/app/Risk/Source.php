<?php

namespace App\Risk;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = 'source';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}