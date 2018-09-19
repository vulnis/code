<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Framework extends Model
{
    protected $table = 'frameworks';
    protected $primaryKey = 'value';
    public $timestamps = false;

    public function parent()
    {
        return $this->belongsTo(Framework::class, 'parent', 'value');
    }

    public function children()
    {
        return $this->hasMany(Framework::class);
    }
}
