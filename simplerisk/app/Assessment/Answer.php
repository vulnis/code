<?php

namespace App\Assessment;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'assessment_answers';
    public $timestamps = false;

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
