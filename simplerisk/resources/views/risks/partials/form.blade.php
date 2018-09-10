<form method="POST" action="{{ url('risk') }}" class="form">
    {{ csrf_field() }}
    <input type='hidden' name='AccessVector' id='AccessVector' value='N' />
    <input type='hidden' name='AccessComplexity' id='AccessComplexity' value='L' />
    <input type='hidden' name='Authentication' id='Authentication' value='N' />
    <input type='hidden' name='ConfImpact' id='ConfImpact' value='C' />
    <input type='hidden' name='IntegImpact' id='IntegImpact' value='C' />
    <input type='hidden' name='AvailImpact' id='AvailImpact' value='C' />
    <input type='hidden' name='Exploitability' id='Exploitability' value='ND' />
    <input type='hidden' name='RemediationLevel' id='RemediationLevel' value='ND' />
    <input type='hidden' name='ReportConfidence' id='ReportConfidence' value='ND' />
    <input type='hidden' name='CollateralDamagePotential' id='CollateralDamagePotential' value='ND' />
    <input type='hidden' name='TargetDistribution' id='TargetDistribution' value='ND' />
    <input type='hidden' name='ConfidentialityRequirement' id='ConfidentialityRequirement' value='ND' />
    <input type='hidden' name='IntegrityRequirement' id='IntegrityRequirement' value='ND' />
    <input type='hidden' name='AvailabilityRequirement' id='AvailabilityRequirement' value='ND' />
    <input type='hidden' name='DREADDamage' id='DREADDamage' value='10' />
    <input type='hidden' name='DREADReproducibility' id='DREADReproducibility' value='10' />
    <input type='hidden' name='DREADExploitability' id='DREADExploitability' value='10' />
    <input type='hidden' name='DREADAffectedUsers' id='DREADAffectedUsers' value='10' />
    <input type='hidden' name='DREADDiscoverability' id='DREADDiscoverability' value='10' />
    <input type='hidden' name='OWASPSkillLevel' id='OWASPSkillLevel' value='10' />
    <input type='hidden' name='OWASPMotive' id='OWASPMotive' value='10' />
    <input type='hidden' name='OWASPOpportunity' id='OWASPOpportunity' value='10' />
    <input type='hidden' name='OWASPSize' id='OWASPSize' value='10' />
    <input type='hidden' name='OWASPEaseOfDiscovery' id='OWASPEaseOfDiscovery' value='10' />
    <input type='hidden' name='OWASPEaseOfExploit' id='OWASPEaseOfExploit' value='10' />
    <input type='hidden' name='OWASPAwareness' id='OWASPAwareness' value='10' />
    <input type='hidden' name='OWASPIntrusionDetection' id='OWASPIntrusionDetection' value='10' />
    <input type='hidden' name='OWASPLossOfConfidentiality' id='OWASPLossOfConfidentiality' value='10' />
    <input type='hidden' name='OWASPLossOfIntegrity' id='OWASPLossOfIntegrity' value='10' />
    <input type='hidden' name='OWASPLossOfAvailability' id='OWASPLossOfAvailability' value='10' />
    <input type='hidden' name='OWASPLossOfAccountability' id='OWASPLossOfAccountability' value='10' />
    <input type='hidden' name='OWASPFinancialDamage' id='OWASPFinancialDamage' value='10' />
    <input type='hidden' name='OWASPReputationDamage' id='OWASPReputationDamage' value='10' />
    <input type='hidden' name='OWASPNonCompliance' id='OWASPNonCompliance' value='10' />
    <input type='hidden' name='OWASPPrivacyViolation' id='OWASPPrivacyViolation' value='10' />
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="risk-subject">@lang('messages.Subject')</label>
                    <input type="text" name="subject" id="risk-subject" class="form-control" @if($risk) value="{{$risk->subject}}" disabled @endif>
                </div>
                <div class="form-group">
                    <label for="risk-category">@lang('messages.Category')</label>
                    <select class="form-control" id="risk-category" name="category" @if($risk) disabled @endif>
                        @foreach ($categories as $i => $category)
                            @if($risk)
                                @if($risk->category === $category->value) 
                                <option selected value="{{ $category->value}}">{{ $category->name }}</option>
                                @else
                                <option value="{{ $category->value}}">{{ $category->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $category->value}}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-source">@lang('messages.RiskSource')</label>
                    <select class="form-control" id="risk-source" name="source" @if($risk) disabled @endif>
                        @foreach ($sources as $i => $source)
                        @if($risk)
                                @if($risk->source === $source->value) 
                                <option selected value="{{ $source->value}}">{{ $source->name }}</option>
                                @else
                                <option value="{{ $source->value}}">{{ $source->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $source->value}}">{{ $source->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-likelihood">@lang('messages.Likelihood')</label>
                    <select class="form-control" id="risk-likelihood" name="likelihood" @if($risk) disabled @endif>
                        @foreach ($probabilities as $i => $probability)
                            @if($risk)
                                @if($risk->score->CLASSIC_likelihood == $probability->value) 
                                <option selected value="{{ $probability->value}}">{{ $probability->name }}</option>
                                @else
                                <option value="{{ $probability->value}}">{{ $probability->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $probability->value}}">{{ $probability->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-impact">@lang('messages.Impact')</label>
                    <select class="form-control" id="risk-impact" name="impact" @if($risk) disabled @endif>
                        @foreach ($impacts as $i => $impact)
                            @if($risk)
                                @if($risk->score->CLASSIC_impact == $impact->value) 
                                <option selected value="{{ $impact->value}}">{{ $impact->name }}</option>
                                @else
                                <option value="{{ $impact->value}}">{{ $impact->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $impact->value}}">{{ $impact->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-notes">@lang('messages.AdditionalNotes')</label>
                    <textarea name="notes" class="form-control" id="risk-notes" rows="3" @if($risk) disabled>{{$risk->notes}}  @else > @endif</textarea>
                </div>
            </div>
            <div class="advanced" style="display:none;">
                <div class="form-group">
                    <label for="risk-location">@lang('messages.SiteLocation')</label>
                    <select class="form-control" id="risk-location" name="location">
                        <option value="" selected>--</option>
                        <option value="1">All Sites</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-reference">@lang('messages.ExternalReferenceId')</label>
                    <input type="text" name="reference" id="risk-reference" class="form-control">
                </div>
                <div class="form-group">
                    <label for="risk-regulation">@lang('messages.ControlRegulation')</label>
                    <select class="form-control" id="risk-regulation" name="regulation">
                        <option value="" selected>--</option>
                        <option value="3">HIPAA</option>
                        <option value="4">ISO 27001</option>
                        <option value="1">PCI DSS</option>
                        <option value="2">Sarbanes-Oxley (SOX)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-control-number">@lang('messages.ControlNumber')</label>
                    <input type="text" name="control-number" id="risk-control-number" class="form-control">
                </div>
                <div class="form-group">
                    <label for="risk-assets">Assets</label>
                    <input type="text" name="assets" id="risk-assets" class="form-control">
                </div>
                <div class="form-group">
                    <label for="risk-technology">@lang('messages.Technology')</label>
                    <select multiple class="form-control" id="risk-technology" name="technology[]">
                        <option value="1">All</option>
                        <option value="2">Anti-Virus</option>
                        <option value="3">Backups</option>
                        <option value="4">Blackberry</option>
                        <option value="5">Citrix</option>
                        <option value="6">Datacenter</option>
                        <option value="7">Mail Routing</option>
                        <option value="8">Live Collaboration</option>
                        <option value="9">Messaging</option>
                        <option value="10">Mobile</option>
                        <option value="11">Network</option>
                        <option value="12">Power</option>
                        <option value="13">Remote Access</option>
                        <option value="14">SAN</option>
                        <option value="15">Telecom</option>
                        <option value="16">Unix</option>
                        <option value="17">VMWare</option>
                        <option value="18">Web</option>
                        <option value="19">Windows</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-team">@lang('messages.Team')</label>
                    <select multiple class="form-control" id="risk-team" name="team[]">
                        <option value="1">Branch Management</option>
                        <option value="2">Collaboration</option>
                        <option value="3">Data Center &amp; Storage</option>
                        <option value="4">Database</option>
                        <option value="5">Information Security</option>
                        <option value="6">IT Systems Management</option>
                        <option value="7">Network</option>
                        <option value="8">Unix</option>
                        <option value="9">Web Systems</option>
                        <option value="10">Windows</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-stakeholders">@lang('messages.AdditionalStakeholders')</label>
                    <select multiple class="form-control" id="risk-stakeholders" name="stakeholders[]">
                        <option value="1">Admin</option>
                        <option value="2">Milo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-owner">@lang('messages.Owner')</label>
                    <select class="form-control" id="risk-owner" name="owner">
                        <option value="1">Admin</option>
                        <option value="2">Milo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-manager">@lang('messages.OwnersManager')</label>
                    <select class="form-control" id="risk-manager" name="manager">
                        <option value="1">Admin</option>
                        <option value="2">Milo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-method">@lang('messages.RiskScoringMethod')</label>
                    <select class="form-control" id="risk-method" name="method">
                        <option value="1" selected>Classic</option>
                        <option value="2">CVSS</option>
                        <option value="3">DREAD</option>
                        <option value="4">OWASP</option>
                        <option value="5">Custom</option>
                    </select>
                </div>
            
            
                <div class="form-group">
                    <label for="risk-assessment">@lang('messages.RiskAssessment')</label>
                    <textarea name="assessment" class="form-control" id="risk-assessment" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="file-upload">@lang('messages.DocumentANewRisk')</label>
                    <input type="file" class="form-control-file" id="file-upload">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!--<input type='button' name='cvssSubmit' id='cvssSubmit' value='Score Using CVSS' />
        <input type='button' name='dreadSubmit' id='dreadSubmit' value='Score Using DREAD' onclick='javascript: popupdread();' />
        <input type='button' name='owaspSubmit' id='owaspSubmit' value='Score Using OWASP' onclick='javascript: popupowasp();' />-->
        @if(!$risk)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-risk-form">@lang('messages.Submit')</button>
        @endif
        <!--<input class="btn pull-right" value="Reset" type="reset">-->
    </div>
</form>