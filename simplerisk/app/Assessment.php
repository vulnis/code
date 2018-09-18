<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Library\RiskCalculator;
use App\Risk\Level;
class Assessment extends Model
{
    protected $table = 'sra';
    protected $hidden = ['severity_id', 'probability_id', 'hazard_id', 'cause_id', 'level_id'];
    protected $appends = ['score', 'sub_id', 'color', 'level'];
    public function risk()
    {
        return $this->belongsTo(Risk::class, 'hazard_id');
    }
    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }
    public function severity()
    {
        return $this->belongsTo(Severity::class, 'severity_id', 'value');
    }
    public function probability()
    {
        return $this->belongsTo(Probability::class, 'probability_id', 'value');
    }

    public function getScoreAttribute()
    {
        $riskCalculator = new RiskCalculator();
        return $riskCalculator->getRiskScore($this->probability->value, $this->severity->value);
    }

    public function mitigations()
    {
        return $this->hasMany(Mitigation::class);
    }

    public function getLevelAttribute()
    {
        $riskCalculator = new RiskCalculator();
        $score = $riskCalculator->getRiskScore($this->probability->value, $this->severity->value);
        $levels = Level::orderBy('value')->get();
        $out = new Level();
        foreach ($levels as $level) {
            if($score > $level->value){
                $out = $level;
            }
        }
        return $out->name;
    }
    public function getColorAttribute()
    {
        $riskCalculator = new RiskCalculator();
        $score = $riskCalculator->getRiskScore($this->probability->value, $this->severity->value);
        $levels = Level::orderBy('value')->get();
        $out = new Level();
        foreach ($levels as $level) {
            if($score > $level->value){
                $out = $level;
            }
        }
        return $out->getColor();
    }
    public function getSubIdAttribute(){
        return $this->risk->id . '-' . $this->cause->id;
    }
}
