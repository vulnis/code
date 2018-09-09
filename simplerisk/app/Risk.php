<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Risk extends Model
{
    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that owns the task.
     */
    public function score()
    {
        return $this->hasOne(Risk\Score::class, 'id');
    }

    const CREATED_AT = 'submission_date';
    const UPDATED_AT = 'last_update';

}
