<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    protected $table = 'risks';
    protected $hidden = ['category','source'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category','value');
    }
    public function source()
    {
        return $this->belongsTo(Source::class, 'source','value');
    }
    public function stage()
    {
        return $this->belongsTo(Risk\Stage::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function score()
    {
        return $this->hasOne(Risk\Score::class, 'id');
    }

    public function mitigations()
    {
        return $this->hasMany(Mitigation::class, 'id');
    }
    const CREATED_AT = 'submission_date';
    const UPDATED_AT = 'last_update';
}
