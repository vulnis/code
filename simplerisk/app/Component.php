<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $table = 'component';
    public $timestamps = false;

    public function parent() {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id','value');
    }
    
    //each category might have multiple children
    public function children() {
        return $this->hasMany(static::class, 'parent_id')->orderBy('name', 'asc');
    }

}