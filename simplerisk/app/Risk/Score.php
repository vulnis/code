<?php

namespace App\Risk;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'risk_scoring';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function risk()
    {
        return $this->belongsTo(Risk::class);
    }

    public function getLevel()
    {
        $levels = Level::orderBy('value')->get();
        $out = new Level();

        foreach ($levels as $level) {
            if($this->calculated_risk > $level->value){
                $out = $level;
            }
        }
        return $out;
    }
}
