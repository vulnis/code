<?php

namespace App\Assessment;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'assessment_scoring';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

}
