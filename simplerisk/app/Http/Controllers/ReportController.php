<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Setting;
use App\Repositories\RiskRepository;

class ReportController extends Controller
{
    protected $risks;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RiskRepository $risks)
    {
        $this->middleware('auth');

        $this->risks = $risks;
    }

    /**
     * Generate an array that will be used to generate the side menu
     */
    private function getSideMenu(){
        $menu = array(
            array(trans('messages.Overview'), 'index.php'),
            array(trans('messages.RiskDashboard'), 'dashboard.php'),
            array(trans('messages.RiskTrend'), 'trend.php'),
            array(trans('messages.DynamicRiskReport'), 'dynamic_risk_report.php'),
            array(trans('messages.RiskAverageOverTime'), 'risk_average_baseline_metric.php'),
            array(trans('messages.LikelihoodImpact'), 'likelihood_impact.php'),
            array(trans('messages.RiskAdvice'), 'riskadvice.php'),
            array(trans('messages.RisksAndAssets'), 'risks_and_assets.php'),
            array(trans('messages.RisksAndControls'), 'risks_and_controls.php'),
            array(trans('messages.AllOpenRisksAssignedToMeByRiskLevel'), 'my_open.php'),
            array(trans('messages.AllOpenRisksNeedingReview'), 'review_needed.php'),
            array(trans('messages.AllOpenRisksByTeamByLevel'), 'risks_open_by_team.php?id=true&risk_status=true&subject=true&calculated_risk=true&submission_date=true&team=true&mitigation_planned=true&management_review=true&owner=true&manager=true'),
            array(trans('messages.HighRiskReport'), 'high.php'),
            array(trans('messages.SubmittedRisksByDate'), 'submitted_by_date.php'),
            array(trans('messages.MitigationsByDate'), 'mitigations_by_date.php'),
            array(trans('messages.ManagementReviewsByDate'), 'mgmt_reviews_by_date.php'),
            array(trans('messages.ClosedRisksByDate'), 'closed_by_date.php'),

        );
        return $menu;
    }

    public function index()
    {
        return view('reports.index',[
            'prefix' => 'reports',
            'menu' => $this->getSideMenu(),
            'collections' => [
                'bystatus' => $this->risks->byStatus(),
                'closedornot' => $this->risks->closedOrNot(),
                'mitigatedornot' => $this->risks->mitigatedOrNot(),
                'reviewedornot' => $this->risks->reviewedOrNot()
            ]
        ]);
    }

}
