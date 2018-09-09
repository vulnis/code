<?php

namespace App\Library;

use App\Risk\Probability;
use App\Risk\Impact;

class RiskCalculator {

    public function getRiskScore($probability, $impact, $model)
    {
        // Required from settings or calculated from tables:
        // Sum of impact
        // Sum of probability
        // Default score as fallback
        //get_table is a simple select *
        $impacts = Impact::count();
        $probabilities = Probability::count();
        
        switch($model)
        {
            case 2:
                // $max_risk = 30;
                $max = ($probabilities * $impacts) + $impacts;
                $value = ($probability * $impact) + $impact;
                break;
            case 3:
                // $max_risk = 25;
                $max = $probabilities * $impacts;
                $value = $probability * $impact;
                break;
            case 4:
                // $max_risk = 30;
                $max = $probabilities * $impacts + $probabilities;
                $value = ($probability * $impact) + $probability;
                break;
            case 5:
                // $max_risk = 35;
                $max = ($probabilities * $impacts) + (2 * $probabilities);
                $value = ($probability * $impact) + (2 * $probability);
                break;
            case 1:
            default:
                // $max_risk = 35;
                $max = ($probabilities * $impacts) + (2 * $impacts);
                $value = ($probability * $impact) + (2 * $impact);
        }
        return round($value * (10 / $max), 1);
    }

}

