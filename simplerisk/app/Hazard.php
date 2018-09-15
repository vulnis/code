<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hazard extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id','value');
    }
    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id','value');
    }
    public function stage()
    {
        return $this->belongsTo(Hazard\Stage::class);
    }
}
