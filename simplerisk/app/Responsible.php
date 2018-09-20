<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Responsible extends Model
{
    protected $table = 'team';
    protected $primaryKey = 'value';
    public $timestamps = false;
}
