@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
            <div class="card-group">
                <div class="card text-center">
                    <div class="card-body">
                        <h1 class="card-title">{{ $hazards }}</h1>
                        <h2 class="card-title">Identified Hazards</h2>
                        <p class="card-text">
                                 <ul>
                                 <li>Surveys</li>
                                 <li>Reports</li>
                                 <li>Analysis</li>
                                 <li>Known vulnerabilities</li>
                                 <li>External sources</li>
                                 </ul>
                        </p>
                        <a href="#" class="card-link">New Hazard</a>
                        <a href="#" class="card-link">List</a>
                    </div>
                </div>
            </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Safety Risk Management Process</div>

                <div class="panel-body">
                    
                     <h2>Risk Assessment</h2>
                    <h5>2. Perform Root cause Analysis</h5>
                    <ul>
                    <li>Root causes</li><li>Consequences</li>
                    </ul>
                    <h2>Risk Mitigation</h2>
                    <h5>3. Assess Safety Risks of the consequences</h5>
                    <ul><li>Probability/Severity</li><li>Risk Matrix</li><li>Risk Index</li></ul>
                    <h2>Risk Management</h2>
                    <h5>4. Define actions for mitigating unacceptable risks</h5>
                    <ul>
                    <li>Risk acceptance matrix</li>
                    <li>CA/PA</li>
                    <li>Decisions</li>
                    </ul>
                    <h5>5. Review the efficiency of the implemented actions</h5>
                    <ul>
                    <li>Review Risk control</li>
                    <li>Performance</li>
                    <li>Documentation/Report</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection