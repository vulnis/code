<?php

namespace App\Assessment;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'assessment_questions';
    public $timestamps = false;

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}
