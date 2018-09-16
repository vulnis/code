<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cause extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id','value');
    }

    public function consequences()
    {
        return $this->belongsToMany(Consequence::class);
    }
}
