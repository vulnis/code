<?php

namespace App\Risk;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    /**
     * Get the user that owns the task.
     */
    public function risk()
    {
        return $this->belongsTo(Risk::class);
    }
}