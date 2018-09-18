<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mitigation extends Model
{
    protected $table = 'mitigations';

    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    const CREATED_AT = 'submission_date';
    const UPDATED_AT = 'last_update';

}
