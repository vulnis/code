<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Highcharts\Chart;

class PieChart extends Chart
{
    protected $title;
    /**
     * Initializes risk_average_baseline_metric
     *
     * @return void
     */
    public function __construct($name, $type, $collection)
    {
        parent::__construct();
        $labels = [];
        $dataset = [];
        $colors = [];
        foreach ($collection as $value)
        {
            $labels[] = trans($value->name) . " (" . $value->risks . ")";
            $dataset[] = $value->risks;
            if(isset($value->color)){
                $colors[] = $value->color;
            } else {
                $colors[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            }
        }
        $this->title = $name;
        $this->labels($labels);
        $this->dataset('#', $type, $dataset)->color($colors);
        $this->options([
            //'title'=>[
            //    'text'=> $name
            //],
            'chart' => [
               'height'=> 300
            ],
            'plotOptions' => [
                'pie' => [
                    'dataLabels' => ['enabled' => false],
                    'showInLegend' => 1
                ]
            ]
        ]);
    }

    public function getTitle(){
        return $this->title;
    }

}
