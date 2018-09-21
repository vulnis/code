@extends('layouts.app')
@section('content')
<div class="row">
    @include('layouts.partials.side')
    <div class="col-9">
        <form name="review_form" method="post" action="">
            <p>
                <h4>@lang('messages.Review')</h4>
                @lang('messages.AddNewReviewNamed') <input name="new_review" type="text" maxlength="50" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_review" /><br />
                @lang('messages.Change') create_dropdown("review", NULL, "update_review_name") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_review" /><br />
                @lang('messages.DeleteCurrentReviewNamed') create_dropdown("review")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_review" />
            </p>
        </form>
        <form name="next_step_form" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.NextStep')</h4>
                                        @lang('messages.AddNewNextstepNamed') <input name="new_next_step" type="text" maxlength="50" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_next_step" /><br />
                                        @lang('messages.Change') create_dropdown("next_step", NULL, "update_next_step_name") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_next_step" /><br />
                                        @lang('messages.DeleteCurrentNextstepNamed') create_dropdown("next_step")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_next_step" />
                                    </p>
                                </form>
                                form name="category" method="post" action="">
                                    <p>
                                        <h4>@choice('messages.Category',1)</h4>
                                        @lang('messages.AddNewCategoryNamed') <input name="new_category" type="text" maxlength="50" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_category" /><br />
                                        @lang('messages.Change') create_dropdown("category", NULL, "update_category_name") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_category" /><br />
                                        @lang('messages.DeleteCurrentCategoryNamed') create_dropdown("category")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_category" />
                                    </p>
                                </form>
                                <form name="team" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.Team')</h4>
                                        @lang('messages.AddNewTeamNamed') <input name="new_team" type="text" maxlength="50" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_team" /><br />
                                        @lang('messages.Change') create_dropdown("team", NULL, "update_team_name") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_team" /><br />
                                        @lang('messages.DeleteCurrentTeamNamed') create_dropdown("team")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_team" />
                                    </p>
                                </form>
                                <form name="technology" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.Technology')</h4>
                                        @lang('messages.AddNewTechnologyNamed') <input name="new_technology" type="text" maxlength="50" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_technology" /><br />
                                        @lang('messages.Change') create_dropdown("technology", NULL, "update_technology_name") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_technology" /><br />
                                        @lang('messages.DeleteCurrentTechnologyNamed') create_dropdown("technology")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_technology" />
                                    </p>
                                </form>
                                <form name="location" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.SiteLocation')</h4>
                                        @lang('messages.AddNewSiteLocationNamed') <input name="new_location" type="text" maxlength="100" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_location" /><br />
                                        @lang('messages.Change') create_dropdown("location", NULL, "update_location_name") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_location" /><br />
                                        @lang('messages.DeleteCurrentSiteLocationNamed') create_dropdown("location")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_location" />
                                    </p>
                                </form>
                                <form name="regulation" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.ControlRegulation')</h4>
                                        @lang('messages.AddNewControlRegulationNamed') <input name="new_regulation" type="text" maxlength="50" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_regulation" /><br />
                                        @lang('messages.Change') create_dropdown("regulation", NULL, "update_regulation_name") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_regulation" /><br />
                                        @lang('messages.DeleteCurrentControlRegulationNamed') create_dropdown("regulation")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_regulation" />
                                    </p>
                                </form>
                                <form name="planning_strategy" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.RiskPlanningStrategy')</h4>
                                        @lang('messages.AddNewRiskPlanningStrategyNamed') <input name="new_planning_strategy" type="text" maxlength="20" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_planning_strategy" /><br />
                                        @lang('messages.Change') create_dropdown("planning_strategy", NULL, "update_planning_strategy_name") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_planning_strategy" /><br />
                                        @lang('messages.DeleteCurrentRiskPlanningStrategyNamed') create_dropdown("planning_strategy")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_planning_strategy" />
                                    </p>
                                </form>
                                <form name="close_reason" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.CloseReason')</h4>
                                        @lang('messages.AddNewCloseReasonNamed') <input name="new_close_reason" type="text" maxlength="20" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_close_reason" /><br />
                                        @lang('messages.Change') create_dropdown("close_reason", NULL, "update_close_reason_name") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_close_reason" /><br />
                                        @lang('messages.DeleteCurrentCloseReasonNamed') create_dropdown("close_reason")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_close_reason" />
                                    </p>
                                </form>
                                <form name="status" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.Status')</h4>
                                        @lang('messages.AddNewStatusNamed') <input name="new_status" type="text" maxlength="20" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_status" /><br />
                                        @lang('messages.Change') create_dropdown("status", NULL, "update_status_name") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_status" /><br />
                                        @lang('messages.DeleteStatusNamed') create_dropdown("status")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_status" />
                                    </p>
                                </form>
                                <form name="source" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.RiskSource')</h4>
                                        @lang('messages.AddNewSourceNamed') <input name="new_source" type="text" maxlength="20" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_source" /><br />
                                        @lang('messages.Change') create_dropdown("source", NULL, "update_source_name") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_source" /><br />
                                        @lang('messages.DeleteSourceNamed') create_dropdown("source")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_source" />
                                    </p>
                                </form>
                                <form name="control_class_form" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.ControlClass')</h4>
                                        @lang('messages.AddNewControlClassNamed') <input name="new_control_class" type="text" maxlength="20" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_control_class" /><br />
                                        @lang('messages.Change') create_dropdown("control_class", NULL, "update_value") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_control_class" /><br />
                                        @lang('messages.DeleteCurrentControlClassNamed') create_dropdown("control_class")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_control_class" />
                                    </p>
                                </form>
                                <form name="control_phase_form" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.ControlPhase')</h4>
                                        @lang('messages.AddNewControlPhaseNamed') <input name="new_control_phase" type="text" maxlength="200" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_control_phase" /><br />
                                        @lang('messages.Change') create_dropdown("control_phase", NULL, "update_value") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_control_phase" /><br />
                                        @lang('messages.DeleteCurrentControlPhaseNamed') create_dropdown("control_phase")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_control_phase" />
                                    </p>
                                </form>  <form name="control_priority" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.ControlPriority')</h4>
                                        @lang('messages.AddNewControlPriorityNamed') <input name="new_control_priority" type="text" maxlength="20" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_control_priority" /><br />
                                        @lang('messages.Change') create_dropdown("control_priority", NULL, "update_value") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_control_priority" /><br />
                                        @lang('messages.DeleteCurrentControlPriorityNamed') create_dropdown("control_priority")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_control_priority" />
                                    </p>
                                </form>  <form name="family" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.ControlFamily')</h4>
                                        @lang('messages.AddNewControlFamilyNamed') <input name="new_family" type="text" maxlength="20" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_family" /><br />
                                        @lang('messages.Change') create_dropdown("family", NULL, "update_value") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_family" /><br />
                                        @lang('messages.DeleteCurrentControlFamilyNamed') create_dropdown("family")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_family" />
                                    </p>
                                </form>
                                <form name="test_status_form" method="post" action="">
                                    <p>
                                        <h4>@lang('messages.AuditStatus')</h4>
                                        @lang('messages.AddNewStatusNamed') <input name="new_status" type="text" maxlength="20" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Add')" name="add_test_status" /><br />
                                        @lang('messages.Change') create_dropdown("test_status", NULL, "update_value") @lang('messages.to') <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="@lang('messages.Update')" name="update_test_status" /><br />
                                        @lang('messages.DeleteCurrentStatusNamed') create_dropdown("test_status")&nbsp;&nbsp;<input type="submit" value="@lang('messages.Delete')" name="delete_test_status" />
                                    </p>
                                </form>
    </div>
</div>
@endsection