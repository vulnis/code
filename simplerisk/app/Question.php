<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'assessment_questions';
    public $timestamps = false;
}
