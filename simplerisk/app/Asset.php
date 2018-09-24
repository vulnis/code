<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'assets';
    protected $hidden = ['category_id'];
    public $timestamps = false;
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id','value');
    }
}