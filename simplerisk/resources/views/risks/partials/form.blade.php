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
            
            <div class="form-group">
                <label for="risk-subject">Subject</label>
                <input type="text" name="subject" id="risk-subject" class="form-control">
            </div>
            <div class="form-group">
                <label for="risk-category">Category</label>
                <select class="form-control" id="risk-category" name="category">
                    <option value="" selected>--</option>
                    <option value="1">Access Management</option>
                    <option value="2">Environmental Resilience</option>
                    <option value="3">Monitoring</option>
                    <option value="4">Physical Security</option>
                    <option value="5">Policy and Procedure</option>
                    <option value="6">Sensitive Data Management</option>
                    <option value="7">Technical Vulnerability Management</option>
                    <option value="8">Third-Party Management</option>
                </select>
            </div>
            <div class="form-group">
                <label for="risk-location">Location</label>
                <select class="form-control" id="risk-location" name="location">
                    <option value="" selected>--</option>
                    <option value="1">All Sites</option>
                </select>
            </div>
            <div class="form-group">
                <label for="risk-reference">External Reference ID</label>
                <input type="text" name="reference" id="risk-reference" class="form-control">
            </div>
            <div class="form-group">
                <label for="risk-regulation">Regulation</label>
                <select class="form-control" id="risk-regulation" name="regulation">
                    <option value="" selected>--</option>
                    <option value="3">HIPAA</option>
                    <option value="4">ISO 27001</option>
                    <option value="1">PCI DSS</option>
                    <option value="2">Sarbanes-Oxley (SOX)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="risk-control-number">Control number</label>
                <input type="text" name="control-number" id="risk-control-number" class="form-control">
            </div>
            <div class="form-group">
                <label for="risk-assets">Assets</label>
                <input type="text" name="assets" id="risk-assets" class="form-control">
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            
            <div class="form-group">
                <label for="risk-technology">Technology</label>
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
                <label for="risk-team">Team</label>
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
                <label for="risk-stakeholders">Stakeholders</label>
                <select multiple class="form-control" id="risk-stakeholders" name="stakeholders[]">
                    <option value="1">Admin</option>
                    <option value="2">Milo</option>
                </select>
            </div>
            <div class="form-group">
                <label for="risk-owner">Owner</label>
                <select class="form-control" id="risk-owner" name="owner">
                    <option value="1">Admin</option>
                    <option value="2">Milo</option>
                </select>
            </div>
            <div class="form-group">
                <label for="risk-manager">Manager</label>
                <select class="form-control" id="risk-manager" name="manager">
                    <option value="1">Admin</option>
                    <option value="2">Milo</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="form-group">
                <label for="risk-source">Source</label>
                <select class="form-control" id="risk-source" name="source">
                    <option value="" selected>--</option>
                    <option value="4">External</option>
                    <option value="1">People</option>
                    <option value="2">Process</option>
                    <option value="3">System</option>
                </select>
            </div>
            <div class="form-group">
                <label for="risk-method">Scoring method</label>
                <select class="form-control" id="risk-method" name="method">
                    <option value="1" selected>Classic</option>
                    <option value="2">CVSS</option>
                    <option value="3">DREAD</option>
                    <option value="4">OWASP</option>
                    <option value="5">Custom</option>
                </select>
            </div>
            <div class="form-group">
                <label for="risk-likelihood">Likelihood</label>
                <select class="form-control" id="risk-likelihood" name="likelihood">
                    <option value="" selected>--</option>
                    <option value="1">Remote</option>
                    <option value="2">Unlikely</option>
                    <option value="3">Credible</option>
                    <option value="4">Likely</option>
                    <option value="5">Almost Certain</option>
                </select>
            </div>
            <div class="form-group">
                <label for="risk-impact">Impact</label>
                <select class="form-control" id="risk-impact" name="impact">
                    <option value="" selected>--</option>
                    <option value="1">Insignificant</option>
                    <option value="2">Minor</option>
                    <option value="3">Moderate</option>
                    <option value="4">Major</option>
                    <option value="5">Extreme/Catastrophic</option>
                </select>
            </div>
            <div class="form-group">
                <label for="risk-assessment">Assessment</label>
                <textarea name="assessment" class="form-control" id="risk-assessment" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="risk-notes">Notes</label>
                <textarea name="notes" class="form-control" id="risk-notes" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="file-upload">Attachments</label>
                <input type="file" class="form-control-file" id="file-upload">
            </div>
        </div>
    </div>
    <div class="row">
        <!--<input type='button' name='cvssSubmit' id='cvssSubmit' value='Score Using CVSS' />
        <input type='button' name='dreadSubmit' id='dreadSubmit' value='Score Using DREAD' onclick='javascript: popupdread();' />
        <input type='button' name='owaspSubmit' id='owaspSubmit' value='Score Using OWASP' onclick='javascript: popupowasp();' />-->
        <button type="submit" name="submit" class="btn btn-primary pull-right save-risk-form">Submit</button>
        <!--<input class="btn pull-right" value="Reset" type="reset">-->
    </div>
</form>