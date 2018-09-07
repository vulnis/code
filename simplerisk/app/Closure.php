<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Risk;

class Closure extends Model
{
    protected $table = 'closures';
    /**
     * Get the comments for the blog post.
     */
    public function risk()
    {
        return $this->hasOne('App\Risk','risk_id');
    }
}
