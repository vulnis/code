<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    protected $table = 'risks';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function score()
    {
        return $this->hasOne(Risk\Score::class, 'id');
    }

    public function mitigations()
    {
        return $this->hasMany(Mitigation::class, 'id');
    }
    const CREATED_AT = 'submission_date';
    const UPDATED_AT = 'last_update';

}
