<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Framework extends Model
{
    protected $table = 'frameworks';
    protected $primaryKey = 'value';
    public $timestamps = false;

    public function super()
    {
        return $this->belongsTo(static::class, 'parent', 'value');
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent')->orderBy('name', 'asc');
    }
}
