<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Assessment;
class AssessmentRepository
{
    /**
     * Get the risk count by status.
     *
     * @return Collection
     */
    public function getAssessments()
    {
        return Assessment::all();
    }

}