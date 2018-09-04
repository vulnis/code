<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
* License, v. 2.0. If a copy of the MPL was not distributed with this
* file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Include required functions file
require_once(realpath(__DIR__ . '/../includes/functions.php'));
require_once(realpath(__DIR__ . '/../includes/authenticate.php'));
require_once(realpath(__DIR__ . '/../includes/display.php'));
require_once(realpath(__DIR__ . '/../includes/assets.php'));
require_once(realpath(__DIR__ . '/../includes/alerts.php'));
require_once(realpath(__DIR__ . '/../includes/permissions.php'));
require_once(realpath(__DIR__ . '/../includes/governance.php'));
require_once(realpath(__DIR__ . '/../includes/compliance.php'));

// Include Zend Escaper for HTML Output Encoding
require_once(realpath(__DIR__ . '/../includes/Component_ZendEscaper/Escaper.php'));
$escaper = new Zend\Escaper\Escaper('utf-8');

// Add various security headers
add_security_headers();

// Session handler is database
if (USE_DATABASE_FOR_SESSIONS == "true")
{
  session_set_save_handler('sess_open', 'sess_close', 'sess_read', 'sess_write', 'sess_destroy', 'sess_gc');
}

// Start the session
session_set_cookie_params(0, '/', '', isset($_SERVER["HTTPS"]), true);

if (!isset($_SESSION))
{
        session_name('SimpleRisk');
        session_start();
}

// Load CSRF Magic
require_once(realpath(__DIR__ . '/../includes/csrf-magic/csrf-magic.php'));

// Include the language file
require_once(language_file());

// Check for session timeout or renegotiation
session_check();

// Check if access is authorized
if (!isset($_SESSION["access"]) || $_SESSION["access"] != "granted")
{
  set_unauthenticated_redirect();
  header("Location: ../index.php");
  exit(0);
}

// Enforce that the user has access to compliance
enforce_permission_compliance();

// Check if a framework was updated
if (isset($_POST['update_framework']))
{
  $framework_id = (int)$_POST['framework_id'];
  $parent       = (int)$_POST['parent'];
  $name         = $escaper->escapeHtml($_POST['framework_name']);
  $descripiton  = $escaper->escapeHtml($_POST['framework_description']);

  // Check if the framework name is null
  if (isset($name) && $name == "")
  {
    // Display an alert
    set_alert(true, "bad", "The framework name cannot be empty.");
  }
  // Otherwise
  else
  {
    // Check if user has a permission to modify framework
    if(empty($_SESSION['modify_frameworks']))
    {
        set_alert(true, "bad", $escaper->escapeHtml($lang['NoModifyFrameworkPermission']));
    }
    // Update framework
    elseif(update_framework($framework_id, $name, $descripiton, $parent))
    {
        // Display an alert
        set_alert(true, "good", $escaper->escapeHtml($lang['FrameworkUpdated']));
    }
    else
    {
        // Display an alert
        set_alert(true, "bad", $escaper->escapeHtml($lang['FrameworkNameExist']));
    }

  }
  refresh();
}

// Update if a control was updated
if (isset($_POST['update_control']))
{
  $control_id = (int)$_POST['control_id'];

  // If user has no permission to modify controls
  if(empty($_SESSION['modify_controls']))
  {
      // Display an alert
      set_alert(true, "bad", $escaper->escapeHtml($lang['NoModifyControlPermission']));
  }
  // Verify value is an integer
  elseif (is_int($control_id))
  {
      $control = array(
        'short_name' => isset($_POST['short_name']) ? $_POST['short_name'] : "",
        'long_name' => isset($_POST['long_name']) ? $_POST['long_name'] : "",
        'description' => isset($_POST['description']) ? $_POST['description'] : "",
        'supplemental_guidance' => isset($_POST['supplemental_guidance']) ? $_POST['supplemental_guidance'] : "",
        'framework_ids' => isset($_POST['frameworks']) ? $_POST['frameworks'] : "",
        'control_owner' => isset($_POST['control_owner']) ? (int)$_POST['control_owner'] : 0,
        'control_class' => isset($_POST['control_class']) ? (int)$_POST['control_class'] : 0,
        'control_phase' => isset($_POST['control_phase']) ? (int)$_POST['control_phase'] : 0,
        'control_number' => isset($_POST['control_number']) ? $_POST['control_number'] : "",
        'control_priority' => isset($_POST['control_priority']) ? (int)$_POST['control_priority'] : 0,
        'family' => isset($_POST['family']) ? (int)$_POST['family'] : 0
      );
      // Update the control
      update_framework_control($control_id, $control);

      // Display an alert
      set_alert(true, "good", "An existing control was updated successfully.");
  }
  // We should never get here as we bound the variable as an int
  else
  {
    // Display an alert
    set_alert(true, "bad", "The control ID was not a valid value.  Please try again.");
  }
  
  // Refresh current page
  refresh();
}

// Check if editing test
if(isset($_POST['update_test'])){
    $test_id        = (int)$_POST['test_id'];
    $tester         = (int)$_POST['tester'];
    $test_frequency = (int)$_POST['test_frequency'];
    $last_date      = get_standard_date_from_default_format($_POST['last_date']);
    $next_date      = get_standard_date_from_default_format($_POST['next_date']);
    $name           = $escaper->escapeHtml($_POST['name']);
    $objective      = $escaper->escapeHtml($_POST['objective']);
    $test_steps     = $escaper->escapeHtml($_POST['test_steps']);
    $approximate_time = (int)$_POST['approximate_time'];
    $expected_results = $escaper->escapeHtml($_POST['expected_results']);
    
    // Update a framework control test
    update_framework_control_test($test_id, $tester, $test_frequency, $name, $objective, $test_steps, $approximate_time, $expected_results, $last_date, $next_date);
    
    set_alert(true, "good", $escaper->escapeHtml($lang['TestSuccessUpdated']));
    
    // Refresh current page
    refresh();
}

// Check if initiate framework or control or test
if(isset($_GET['initiate']) ){
    $id     = (int)$_GET['id'];
    $type   = $escaper->escapeHtml($_GET['type']);
    
    if($name = initiate_framework_control_tests($type, $id)){
        if($type == 'framework'){
            set_alert(true, "good", $escaper->escapeHtml(_lang('InitiatedAllTestsUnderFramework', ['framework' => $name])));
        }elseif($type == 'control'){
            set_alert(true, "good", $escaper->escapeHtml(_lang('InitiatedAllTestsUnderControl', ['control' => $name])));
        }elseif($type == 'test'){
            set_alert(true, "good", $escaper->escapeHtml(_lang('InitiatedTest', ['test' => $name])));
        }
    }
    
    // Go back to old page
    refresh($_SESSION['base_url']."/compliance/audit_initiation.php");
}


?>
<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); ?>
<body>
    <?php
        view_top_menu("Compliance");

        // Get any alert messages
        get_alert();
    ?>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <?php view_compliance_menu("InitialAudits"); ?>
            </div>
            <div class="col-9 compliance-content-container content-margin-height">
                <div id="show-alert"></div>
                <div class="row">
                    <div class="col-12">
                        <div class="custom-treegrid-container" id="initiate-audits">
                            <?php display_initiate_audits(); ?>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
    
    <script type="">
        
    </script>

    <!-- MODEL WINDOW FOR EDITING FRAMEWORK -->
    <div id="framework--update" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-body">
        <form class="" action="#" method="post" autocomplete="off">
            <input type="hidden" class="framework_id" name="framework_id" value=""> 
            <div class="form-group">
                <label for=""><?php echo $escaper->escapeHtml($lang['FrameworkName']); ?></label>
                <input type="text" required name="framework_name" value="" class="form-control" autocomplete="off">

                <label for=""><?php echo $escaper->escapeHtml($lang['ParentFramework']); ?></label>
                <div class="parent_frameworks_container">
                </div>

                <label for=""><?php echo $escaper->escapeHtml($lang['FrameworkDescription']); ?></label>
                <textarea name="framework_description" value="" class="form-control" rows="6" style="width:100%;"></textarea>
            </div>

            <div class="form-group text-right">
                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
                <button type="submit" name="update_framework" class="btn btn-danger"><?php echo $escaper->escapeHtml($lang['Update']); ?></button>
            </div>
        </form>
      </div>
    </div>

    <!-- MODEL WINDOW FOR UPDATING CONTROL -->
    <div id="control--update" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="control--update" aria-hidden="true">
      <div class="modal-body">
        <form class="" id="control--new" action="#controls-tab" method="post" autocomplete="off">
            <input type="hidden" class="control_id" name="control_id" value=""> 
          <div class="form-group">
            <label for=""><?php echo $escaper->escapeHtml($lang['ControlShortName']); ?></label>
            <input type="text" name="short_name" value="" class="form-control">
            
            <label for=""><?php echo $escaper->escapeHtml($lang['ControlLongName']); ?></label>
            <input type="text" name="long_name" value="" class="form-control">
            
            <label for=""><?php echo $escaper->escapeHtml($lang['ControlDescription']); ?></label>
            <textarea name="description" value="" class="form-control" rows="6" style="width:100%;"></textarea>
            
            <label for=""><?php echo $escaper->escapeHtml($lang['SupplementalGuidance']); ?></label>
            <textarea name="supplemental_guidance" value="" class="form-control" rows="6" style="width:100%;"></textarea>

            <label for=""><?php echo $escaper->escapeHtml($lang['ControlOwner']); ?></label>
            <?php create_dropdown("user", NULL, "control_owner", true, false, false, "", $escaper->escapeHtml($lang['Unassigned'])); ?>

            <label for=""><?php echo $escaper->escapeHtml($lang['ControlFrameworks']); ?></label>
            <?php create_multiple_dropdown("frameworks", NULL); ?>

            <label for=""><?php echo $escaper->escapeHtml($lang['ControlClass']); ?></label>
            <?php create_dropdown("control_class", NULL, "control_class", true, false, false, "", $escaper->escapeHtml($lang['Unassigned'])); ?>

            <label for=""><?php echo $escaper->escapeHtml($lang['ControlPhase']); ?></label>
            <?php create_dropdown("control_phase", NULL, "control_phase", true, false, false, "", $escaper->escapeHtml($lang['Unassigned'])); ?>

            <label for=""><?php echo $escaper->escapeHtml($lang['ControlNumber']); ?></label>
            <input type="text" name="control_number" value="" class="form-control">

            <label for=""><?php echo $escaper->escapeHtml($lang['ControlPriority']); ?></label>
            <?php create_dropdown("control_priority", NULL, "control_priority", true, false, false, "", $escaper->escapeHtml($lang['Unassigned'])); ?>

            <label for=""><?php echo $escaper->escapeHtml($lang['ControlFamily']); ?></label>
            <?php create_dropdown("family", NULL, "family", true, false, false, "", $escaper->escapeHtml($lang['Unassigned'])); ?>
          </div>
          
          <div class="form-group text-right">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
            <button type="submit" name="update_control" class="btn btn-danger"><?php echo $escaper->escapeHtml($lang['Update']); ?></button>
          </div>
        </form>

      </div>
    </div>
    
    <!-- MODEL WINDOW FOR EDITING TEST -->
    <div id="test--edit" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-body">
        <form class="" id="test-edit-form" method="post" autocomplete="off">
          <div class="form-group">
            <label for=""><?php echo $escaper->escapeHtml($lang['TestName']); ?></label>
            <input type="text" name="name" value="" class="form-control">

            <label for=""><?php echo $escaper->escapeHtml($lang['Tester']); ?></label>
            <?php create_dropdown("user", NULL, "tester", false, false, false); ?>
            
            <label for=""><?php echo $escaper->escapeHtml($lang['TestFrequency']); ?></label>
            <input type="number" name="test_frequency" value="" class="form-control"> <span class="white-labels">(<?php echo $escaper->escapeHtml($lang['days']); ?>)</span>
            
            <label for=""><?php echo $escaper->escapeHtml($lang['LastTestDate']); ?></label>
            <input type="text" name="last_date" value="" class="form-control datepicker"> 
            
            <label for=""><?php echo $escaper->escapeHtml($lang['NextTestDate']); ?></label>
            <input type="text" name="next_date" value="" class="form-control datepicker"> 
            
            <label for=""><?php echo $escaper->escapeHtml($lang['Objective']); ?></label>
            <textarea name="objective" class="form-control" rows="6" style="width:100%;"></textarea>

            <label for=""><?php echo $escaper->escapeHtml($lang['TestSteps']); ?></label>
            <textarea name="test_steps" class="form-control" rows="6" style="width:100%;"></textarea>

            <label for=""><?php echo $escaper->escapeHtml($lang['ApproximateTime']); ?></label>
            <input type="number" name="approximate_time" value="" class="form-control"> <span class="white-labels">(<?php echo $escaper->escapeHtml($lang['minutes']); ?>)</span>

            <label for=""><?php echo $escaper->escapeHtml($lang['ExpectedResults']); ?></label>
            <textarea name="expected_results" class="form-control" rows="6" style="width:100%;"></textarea>

            <input type="hidden" name="test_id" value="">

          </div>
          
          <div class="form-group text-right">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
            <button type="submit" name="update_test" class="btn btn-danger"><?php echo $escaper->escapeHtml($lang['Update']); ?></button>
          </div>
        </form>

      </div>
    </div>
    <?php display_set_default_date_format_script(); ?>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>
</body>
</html>