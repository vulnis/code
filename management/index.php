<?php
        /* This Source Code Form is subject to the terms of the Mozilla Public
         * License, v. 2.0. If a copy of the MPL was not distributed with this
         * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

        // Include required functions file
        require_once('../includes/functions.php');
        require_once('../includes/authenticate.php');

        // Add various security headers
        header("X-Frame-Options: DENY");
        header("X-XSS-Protection: 1; mode=block");

        // If we want to enable the Content Security Policy (CSP) - This may break Chrome
        if (CSP_ENABLED == "true")
        {
                // Add the Content-Security-Policy header
                header("Content-Security-Policy: default-src 'self'; script-src 'unsafe-inline'; style-src 'unsafe-inline'");
        }

        // Session handler is database
        if (USE_DATABASE_FOR_SESSIONS == "true")
        {
		session_set_save_handler('sess_open', 'sess_close', 'sess_read', 'sess_write', 'sess_destroy', 'sess_gc');
        }

        // Start the session
	session_set_cookie_params(0, '/', '', isset($_SERVER["HTTPS"]), true);
        session_start('SimpleRisk');

        // Check for session timeout or renegotiation
        session_check();

	// Default is no alert
	$alert = false;

        // Check if access is authorized
        if (!isset($_SESSION["access"]) || $_SESSION["access"] != "granted")
        {
                header("Location: ../index.php");
                exit(0);
        }

	// Check if the user has access to submit risks
	if (!isset($_SESSION["submit_risks"]) || $_SESSION["submit_risks"] != 1)
	{
		$submit_risks = false;
		$alert = "bad";
		$alert_message = "You do not have permission to submit new risks.  Any risks that you attempt to submit will not be recorded.  Please contact an Administrator if you feel that you have reached this message in error.";
	}
	else $submit_risks = true;

        // Check if a new risk was submitted and the user has permissions to submit new risks
        if ((isset($_POST['submit'])) && $submit_risks)
        {
                $status = "New";
                $subject = addslashes($_POST['subject']);
		$reference_id = addslashes($_POST['reference_id']);
		$regulation = (int)$_POST['regulation'];
		$control_number = addslashes($_POST['control_number']);
		$location = addslashes($_POST['location']);
                $category = (int)$_POST['category'];
                $team = (int)$_POST['team'];
                $technology = (int)$_POST['technology'];
                $owner = (int)$_POST['owner'];
                $manager = (int)$_POST['manager'];
                $assessment = addslashes($_POST['assessment']);
                $notes = addslashes($_POST['notes']);

		// Risk scoring method
		// 1 = Classic
		// 2 = CVSS
		// 3 = DREAD
		// 4 = OWASP
		// 5 = Custom
		$scoring_method = (int)$_POST['scoring_method'];

		// Classic Risk Scoring Inputs
		$CLASSIClikelihood = (int)$_POST['likelihood'];
                $CLASSICimpact =(int) $_POST['impact'];

		// CVSS Risk Scoring Inputs
		$CVSSAccessVector = $_POST['AccessVector'];
		$CVSSAccessComplexity = $_POST['AccessComplexity'];
		$CVSSAuthentication = $_POST['Authentication'];
		$CVSSConfImpact = $_POST['ConfImpact'];
		$CVSSIntegImpact = $_POST['IntegImpact'];
		$CVSSAvailImpact = $_POST['AvailImpact'];
		$CVSSExploitability = $_POST['Exploitability'];
		$CVSSRemediationLevel = $_POST['RemediationLevel'];
		$CVSSReportConfidence = $_POST['ReportConfidence'];
		$CVSSCollateralDamagePotential = $_POST['CollateralDamagePotential'];
		$CVSSTargetDistribution = $_POST['TargetDistribution'];
		$CVSSConfidentialityRequirement = $_POST['ConfidentialityRequirement'];
		$CVSSIntegrityRequirement = $_POST['IntegrityRequirement'];
		$CVSSAvailabilityRequirement = $_POST['AvailabilityRequirement'];

		// DREAD Risk Scoring Inputs
		$DREADDamage = (int)$_POST['DREADDamage'];
		$DREADReproducibility = (int)$_POST['DREADReproducibility'];
		$DREADExploitability = (int)$_POST['DREADExploitability'];
		$DREADAffectedUsers = (int)$_POST['DREADAffectedUsers'];
		$DREADDiscoverability = (int)$_POST['DREADDiscoverability'];

		// OWASP Risk Scoring Inputs
		$OWASPSkillLevel = (int)$_POST['OWASPSkillLevel'];
		$OWASPMotive = (int)$_POST['OWASPMotive'];
		$OWASPOpportunity = (int)$_POST['OWASPOpportunity'];
		$OWASPSize = (int)$_POST['OWASPSize'];
		$OWASPEaseOfDiscovery = (int)$_POST['OWASPEaseOfDiscovery'];
		$OWASPEaseOfExploit = (int)$_POST['OWASPEaseOfExploit'];
		$OWASPAwareness = (int)$_POST['OWASPAwareness'];
		$OWASPIntrusionDetection = (int)$_POST['OWASPIntrusionDetection'];
		$OWASPLossOfConfidentiality = (int)$_POST['OWASPLossOfConfidentiality'];
		$OWASPLossOfIntegrity = (int)$_POST['OWASPLossOfIntegrity'];
		$OWASPLossOfAvailability = (int)$_POST['OWASPLossOfAvailability'];
		$OWASPLossOfAccountability = (int)$_POST['OWASPLossOfAccountability'];
		$OWASPFinancialDamage = (int)$_POST['OWASPFinancialDamage'];
		$OWASPReputationDamage = (int)$_POST['OWASPReputationDamage'];
		$OWASPNonCompliance = (int)$_POST['OWASPNonCompliance'];
		$OWASPPrivacyViolation = (int)$_POST['OWASPPrivacyViolation'];

		// Custom Risk Scoring
		$custom = $_POST['Custom'];

                // Submit risk and get back the id
                $last_insert_id = submit_risk($status, $subject, $reference_id, $regulation, $control_number, $location, $category, $team, $technology, $owner, $manager, $assessment, $notes);

		// Submit risk scoring
		submit_risk_scoring($last_insert_id, $scoring_method, $CLASSIClikelihood, $CLASSICimpact, $CVSSAccessVector, $CVSSAccessComplexity, $CVSSAuthentication, $CVSSConfImpact, $CVSSIntegImpact, $CVSSAvailImpact, $CVSSExploitability, $CVSSRemediationLevel, $CVSSReportConfidence, $CVSSCollateralDamagePotential, $CVSSTargetDistribution, $CVSSConfidentialityRequirement, $CVSSIntegrityRequirement, $CVSSAvailabilityRequirement, $DREADDamage, $DREADReproducibility, $DREADExploitability, $DREADAffectedUsers, $DREADDiscoverability, $OWASPSkillLevel, $OWASPMotive, $OWASPOpportunity, $OWASPSize, $OWASPEaseOfDiscovery, $OWASPEaseOfExploit, $OWASPAwareness, $OWASPIntrusionDetection, $OWASPLossOfConfidentiality, $OWASPLossOfIntegrity, $OWASPLossOfAvailability, $OWASPLossOfAccountability, $OWASPFinancialDamage, $OWASPReputationDamage, $OWASPNonCompliance, $OWASPPrivacyViolation, $custom);

		// Audit log
		$risk_id = $last_insert_id + 1000;
		$message = "A new risk ID \"" . $risk_id . "\" was submitted by username \"" . $_SESSION['user'] . "\".";
		write_log($risk_id, $_SESSION['uid'], $message);

		// There is an alert message
		$alert = "good";
		$alert_message = "Risk submitted successfully!";
        }
?>

<!doctype html>
<html>
  
  <head>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <title>SimpleRisk: Enterprise Risk Management Simplified</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/bootstrap-responsive.css"> 
    <script type="text/javascript">
      function popupcvss()
      {
        my_window = window.open('cvss_rating.php','popupwindow','width=850,height=680,menu=0,status=0');
      }

      function popupdread()
      {
        my_window = window.open('dread_rating.php','popupwindow','width=660,height=500,menu=0,status=0');
      }

      function popupowasp()
      {
        my_window = window.open('owasp_rating.php','popupwindow','width=665,height=570,menu=0,status=0');
      }

      function closepopup()
      {
        if(false == my_window.closed)
        {
          my_window.close ();
        }
        else
        {
          alert('Window already closed!');
        }
      }

      function handleSelection(choice) {
        if (choice=="1") {
	  document.getElementById("classic").style.display = "";
          document.getElementById("cvss").style.display = "none";
          document.getElementById("dread").style.display = "none";
          document.getElementById("owasp").style.display = "none";
          document.getElementById("custom").style.display = "none";
	}
        if (choice=="2") {
          document.getElementById("classic").style.display = "none";
          document.getElementById("cvss").style.display = "";
          document.getElementById("dread").style.display = "none";
          document.getElementById("owasp").style.display = "none";
          document.getElementById("custom").style.display = "none";
	}
        if (choice=="3") {
          document.getElementById("classic").style.display = "none";
          document.getElementById("cvss").style.display = "none";
          document.getElementById("dread").style.display = "";
          document.getElementById("owasp").style.display = "none";
          document.getElementById("custom").style.display = "none";
        }
        if (choice=="4") {
          document.getElementById("classic").style.display = "none";
          document.getElementById("cvss").style.display = "none";
          document.getElementById("dread").style.display = "none";
          document.getElementById("owasp").style.display = "";
          document.getElementById("custom").style.display = "none";
        }
        if (choice=="5") {
          document.getElementById("classic").style.display = "none";
          document.getElementById("cvss").style.display = "none";
          document.getElementById("dread").style.display = "none";
          document.getElementById("owasp").style.display = "none";
          document.getElementById("custom").style.display = "";
        }
      }
    </script>
  </head>
  
  <body>
    <title>SimpleRisk: Enterprise Risk Management Simplified</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/bootstrap-responsive.css">
    <link rel="stylesheet" href="../css/divshot-util.css">
    <link rel="stylesheet" href="../css/divshot-canvas.css">
    <link rel="stylesheet" href="../css/display.css">
    <div class="navbar">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="http://www.simplerisk.org/">SimpleRisk</a>
          <div class="navbar-content">
            <ul class="nav">
              <li>
                <a href="../index.php">Home</a> 
              </li>
              <li class="active">
                <a href="index.php">Risk Management</a> 
              </li>
              <li>
                <a href="../reports/index.php">Reporting</a> 
              </li>
<?php
if (isset($_SESSION["admin"]) && $_SESSION["admin"] == "1")
{
          echo "<li>\n";
          echo "<a href=\"../admin/index.php\">Configure</a>\n";
          echo "</li>\n";
}
          echo "</ul>\n";
          echo "</div>\n";

if (isset($_SESSION["access"]) && $_SESSION["access"] == "granted")
{
          echo "<div class=\"btn-group pull-right\">\n";
          echo "<a class=\"btn dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">".$_SESSION['name']."<span class=\"caret\"></span></a>\n";
          echo "<ul class=\"dropdown-menu\">\n";
          echo "<li>\n";
          echo "<a href=\"../account/profile.php\">My Profile</a>\n";
          echo "</li>\n";
          echo "<li>\n";
          echo "<a href=\"../logout.php\">Logout</a>\n";
          echo "</li>\n";
          echo "</ul>\n";
          echo "</div>\n";
}
?>
        </div>
      </div>
    </div>
<?php
        if ($alert == "good")
        {
                echo "<div id=\"alert\" class=\"container-fluid\">\n";
                echo "<div class=\"row-fluid\">\n";
                echo "<div class=\"span12 greenalert\">" . $alert_message . "</div>\n";
                echo "</div>\n";
                echo "</div>\n";
                echo "<br />\n";
        }
        else if ($alert == "bad")
        {
                echo "<div id=\"alert\" class=\"container-fluid\">\n";
                echo "<div class=\"row-fluid\">\n";
                echo "<div class=\"span12 redalert\">" . $alert_message . "</div>\n";
                echo "</div>\n";
                echo "</div>\n";
                echo "<br />\n";
        }
?>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <ul class="nav  nav-pills nav-stacked">
            <li class="active">
              <a href="index.php">I. Submit Your Risks</a> 
            </li>
            <li>
              <a href="plan_mitigations.php">II. Plan Your Mitigations</a> 
            </li>
            <li>
              <a href="management_review.php">III. Perform Management Reviews</a> 
            </li>
            <li>
              <a href="prioritize_planning.php">IV. Prioritize for Project Planning</a> 
            </li>
            <li>
              <a href="review_risks.php">V. Review Risks Regularly</a> 
            </li>
          </ul>
        </div>
        <div class="span9">
          <div class="row-fluid">
            <div class="span12">
              <div class="hero-unit">
                <h4>Document a New Risk</h4>
                <p>Use this form in order to document a new risk for consideration in the Risk Management Process.</p>
                <form name="submit_risk" method="post" action="">
                Subject: <input maxlength="100" name="subject" id="subject" class="input-medium" type="text"><br />
		External Reference ID: <input maxlength="20" size="20" name="reference_id" id="reference_id" class="input-medium" type="text"><br />
		Control Regulation: <?php create_dropdown("regulation"); ?><br />
		Control Number: <input maxlength="20" name="control_number" id="control_number" class="input-medium" type="text"><br />
                Site/Location: <?php create_dropdown("location"); ?><br />
		Category: <?php create_dropdown("category"); ?><br />
		Team: <?php create_dropdown("team"); ?><br />
		Technology: <?php create_dropdown("technology"); ?><br />
		Owner: <?php create_dropdown("user", NULL, "owner"); ?><br />
		Owner&#39;s Manager: <?php create_dropdown("user", NULL, "manager"); ?><br />
		Risk Scoring Method: <select name="scoring_method" id="select" onChange="handleSelection(value)">
		<option selected value="1">Classic</option>
		<option value="2">CVSS</option>
		<option value="3">DREAD</option>
		<option value="4">OWASP</option>
		<option value="5">Custom</option>
		</select>
		<div id="classic">
                Current Likelihood: <?php create_dropdown("likelihood"); ?><br />
                Current Impact: <?php create_dropdown("impact"); ?><br />
		</div>
		<div id="cvss" style="display: none;">
		<p><input type="button" name="cvssSubmit" id="cvssSubmit" value="Score Using CVSS" onclick="javascript: popupcvss();" /></p>
                <input type="hidden" name="AccessVector" id="AccessVector" value="N" />
                <input type="hidden" name="AccessComplexity" id="AccessComplexity" value="L" />
                <input type="hidden" name="Authentication" id="Authentication" value="N" />
                <input type="hidden" name="ConfImpact" id="ConfImpact" value="C" />
                <input type="hidden" name="IntegImpact" id="IntegImpact" value="C" />
                <input type="hidden" name="AvailImpact" id="AvailImpact" value="C" />
                <input type="hidden" name="Exploitability" id="Exploitability" value="ND" />
                <input type="hidden" name="RemediationLevel" id="RemediationLevel" value="ND" />
                <input type="hidden" name="ReportConfidence" id="ReportConfidence" value="ND" />
                <input type="hidden" name="CollateralDamagePotential" id="CollateralDamagePotential" value="ND" />
                <input type="hidden" name="TargetDistribution" id="TargetDistribution" value="ND" />
                <input type="hidden" name="ConfidentialityRequirement" id="ConfidentialityRequirement" value="ND" />
                <input type="hidden" name="IntegrityRequirement" id="IntegrityRequirement" value="ND" />
                <input type="hidden" name="AvailabilityRequirement" id="AvailabilityRequirement" value="ND" />
		</div>
		<div id="dread" style="display: none;">
                <p><input type="button" name="dreadSubmit" id="dreadSubmit" value="Score Using DREAD" onclick="javascript: popupdread();" /></p>
		<input type="hidden" name="DREADDamage" id="DREADDamage" value="10" />
		<input type="hidden" name="DREADReproducibility" id="DREADReproducibility" value="10" />
                <input type="hidden" name="DREADExploitability" id="DREADExploitability" value="10" />
                <input type="hidden" name="DREADAffectedUsers" id="DREADAffectedUsers" value="10" />
                <input type="hidden" name="DREADDiscoverability" id="DREADDiscoverability" value="10" />
		</div>
		<div id="owasp" style="display: none;">
                <p><input type="button" name="owaspSubmit" id="owaspSubmit" value="Score Using OWASP" onclick="javascript: popupowasp();" /></p>
                <input type="hidden" name="OWASPSkillLevel" id="OWASPSkillLevel" value="10" />
                <input type="hidden" name="OWASPMotive" id="OWASPMotive" value="10" />
                <input type="hidden" name="OWASPOpportunity" id="OWASPOpportunity" value="10" />
                <input type="hidden" name="OWASPSize" id="OWASPSize" value="10" />
                <input type="hidden" name="OWASPEaseOfDiscovery" id="OWASPEaseOfDiscovery" value="10" />
                <input type="hidden" name="OWASPEaseOfExploit" id="OWASPEaseOfExploit" value="10" />
                <input type="hidden" name="OWASPAwareness" id="OWASPAwareness" value="10" />
                <input type="hidden" name="OWASPIntrusionDetection" id="OWASPIntrusionDetection" value="10" />
                <input type="hidden" name="OWASPLossOfConfidentiality" id="OWASPLossOfConfidentiality" value="10" />
		<input type="hidden" name="OWASPLossOfIntegrity" id="OWASPLossOfIntegrity" value="10" />
                <input type="hidden" name="OWASPLossOfAvailability" id="OWASPLossOfAvailability" value="10" />
                <input type="hidden" name="OWASPLossOfAccountability" id="OWASPLossOfAccountability" value="10" />
                <input type="hidden" name="OWASPFinancialDamage" id="OWASPFinancialDamage" value="10" />
                <input type="hidden" name="OWASPReputationDamage" id="OWASPReputationDamage" value="10" />
                <input type="hidden" name="OWASPNonCompliance" id="OWASPNonCompliance" value="10" />
                <input type="hidden" name="OWASPPrivacyViolation" id="OWASPPrivacyViolation" value="10" />
		</div>
		<div id="custom" style="display: none;">
		Custom Value: <input type="text" name="Custom" id="Custom" value="" /> (Must be a numeric value between 0 and 10)<br />
		</div>
		<label>Risk Assessment</label>
		<textarea name="assessment" cols="50" rows="3" id="assessment"></textarea>
		<label>Additional Notes</label>
		<textarea name="notes" cols="50" rows="3" id="notes"></textarea>
                <div class="form-actions">
                  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                  <input class="btn" value="Reset" type="reset"> 
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>

</html>
