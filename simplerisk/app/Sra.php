<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sra extends Model
{
    protected $table = 'sra';
    public function hazard()
    {
        return $this->belongsTo(Hazard::class);
    }
    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }
    public function severity()
    {
        return $this->belongsTo(Severity::class);
    }
    public function probability()
    {
        return $this->belongsTo(Probability::class);
    }
}
