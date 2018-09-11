<?php

namespace App\Mitigation;

use Illuminate\Database\Eloquent\Model;

class Effort extends Model
{
    protected $table = 'mitigation_effort';
    protected $primaryKey = 'value';
    public $timestamps = false;

    /**
     * Get the user that owns the task.
     */
    public function mitigation()
    {
        return $this->belongsTo(Mitigation::class);
    }
}