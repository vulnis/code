<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
* License, v. 2.0. If a copy of the MPL was not distributed with this
* file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Include required functions file
require_once(realpath(__DIR__ . '/../includes/functions.php'));
require_once(realpath(__DIR__ . '/../includes/authenticate.php'));
require_once(realpath(__DIR__ . '/../includes/display.php'));
require_once(realpath(__DIR__ . '/../includes/alerts.php'));
require_once(realpath(__DIR__ . '/../includes/permissions.php'));
require_once(realpath(__DIR__ . '/../includes/governance.php'));

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

// Include the language file
require_once(language_file());

function csrf_startup() {
    csrf_conf('rewrite-js', "realpath(__DIR__ . '/../includes/csrf-magic/csrf-magic.js')");
}

// Check for session timeout or renegotiation
session_check();

// Check if access is authorized
if (!isset($_SESSION["access"]) || $_SESSION["access"] != "granted")
{
    set_unauthenticated_redirect();
    header("Location: ../index.php");
    exit(0);
}

// Enforce that the user has access to governance
enforce_permission_governance();

// Check if a new framework was submitted
if (isset($_POST['add_framework']))
{
  $name         = $_POST['new_framework'];
  $descripiton  = $_POST['new_framework_description'];
  $parent       = $_POST['parent'];

  // Check if the framework name is null
  if (isset($name) && $name == "")
  {
    // Display an alert
    set_alert(true, "bad", "The framework name cannot be empty.");
  }
  // Otherwise
  else
  {
    if(empty($_SESSION['add_new_frameworks']))
    {
        // Display an alert
        set_alert(true, "bad", $escaper->escapeHtml($lang['NoAddFrameworkPermission']));
    }
    // Insert a new framework up to 100 chars
    elseif(add_framework($name, $descripiton, $parent)){
        // Display an alert
        set_alert(true, "good", $escaper->escapeHtml($lang['FrameworkAdded']));
    }else{
        // Display an alert
        set_alert(true, "bad", $escaper->escapeHtml($lang['FrameworkNameExist']));
    }

  }
  refresh();
}

// Check if a framework was updated
if (isset($_POST['update_framework']))
{
  $framework_id = (int)$_POST['framework_id'];
  $parent       = (int)$_POST['parent'];
  $name         = $_POST['framework_name'];
  $descripiton  = $_POST['framework_description'];

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
    if(empty($_SESSION['modify_frameworks'])){
        set_alert(true, "bad", $escaper->escapeHtml($lang['NoModifyFrameworkPermission']));
    }
    // Insert a new framework up to 100 chars
    elseif(update_framework($framework_id, $name, $descripiton, $parent))
    {
        // Display an alert
        set_alert(true, "good", $escaper->escapeHtml($lang['FrameworkUpdated']));
    }
    else{
        // Display an alert
        set_alert(true, "bad", $escaper->escapeHtml($lang['FrameworkNameExist']));
    }

  }
  refresh();
}

// Delete if a new framework was submitted
if (isset($_POST['delete_framework']))
{
  $value = (int)$_POST['framework_id'];

  // Verify value is an integer
  if (is_int($value))
  {
    // If user has no permission for modify frameworks
    if(empty($_SESSION['delete_frameworks']))
    {
      set_alert(true, "bad", $escaper->escapeHtml($lang['NoDeleteFrameworkPermission']));
    }
    // If the framework ID is 0 (ie. Unassigned Risks)
    elseif ($value == 0)
    {
      // Display an alert
      set_alert(true, "bad", "You cannot delete the Unassigned framework.  Sorry.");
    }
    else
    {
      // Delete the framework
      delete_frameworks($value);

      // Display an alert
      set_alert(true, "good", "An existing framework was deleted successfully.");
    }
  }
  // We should never get here as we bound the variable as an int
  else
  {
    // Display an alert
    set_alert(true, "bad", "The framework ID was not a valid value.  Please try again.");
  }
  
  refresh();
}

// Check if a new control was submitted
if (isset($_POST['add_control']))
{
  $control = array(
    'short_name' => isset($_POST['short_name']) ? $_POST['short_name'] : "",
    'long_name' => isset($_POST['long_name']) ? $_POST['long_name'] : "",
    'description' => isset($_POST['description']) ? $_POST['description'] : "",
    'supplemental_guidance' => isset($_POST['supplemental_guidance']) ? $_POST['supplemental_guidance'] : "",
    'framework_ids' => isset($_POST['add_framework_ids']) ? $_POST['add_framework_ids'] : "",
    'control_owner' => isset($_POST['control_owner']) ? (int)$_POST['control_owner'] : 0,
    'control_class' => isset($_POST['control_class']) ? (int)$_POST['control_class'] : 0,
    'control_phase' => isset($_POST['control_phase']) ? (int)$_POST['control_phase'] : 0,
    'control_number' => isset($_POST['control_number']) ? $_POST['control_number'] : "",
    'control_priority' => isset($_POST['control_priority']) ? (int)$_POST['control_priority'] : 0,
    'family' => isset($_POST['family']) ? (int)$_POST['family'] : 0,
    'mitigation_percent' => (isset($_POST['mitigation_percent']) && $_POST['mitigation_percent'] >= 0 && $_POST['mitigation_percent'] <= 100) ? (int)$_POST['mitigation_percent'] : 0
  );

  // Check if the control name is null
  if (!$control['short_name'])
  {
    // Display an alert
    set_alert(true, "bad", "The control name cannot be empty.");
  }
  // Otherwise
  else
  {
    // If user has no permission for add new controls
    if(empty($_SESSION['add_new_controls']))
    {
        // Display an alert
        set_alert(true, "bad", $escaper->escapeHtml($lang['NoAddControlPermission']));
    }
    // Insert a new control up to 100 chars
    elseif(add_framework_control($control))
    {
        // Display an alert
        set_alert(true, "good", "A new control was added successfully.");
    }
    else
    {
        // Display an alert
        set_alert(true, "bad", "The control already exists.");
    }
  }
  
  // Refresh current page
  refresh();
}

// Delete if a new control was submitted
if (isset($_POST['delete_control']))
{
  $value = (int)$_POST['control_id'];

  // If user has no permission for delete controls
  if(empty($_SESSION['delete_controls']))
  {
      // Display an alert
      set_alert(true, "bad", $escaper->escapeHtml($lang['NoDeleteControlPermission']));
  }
  // Verify value is an integer
  elseif (is_int($value))
  {
      // Delete the control
      delete_framework_control($value);

      // Display an alert
      set_alert(true, "good", "An existing control was deleted successfully.");
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
        'family' => isset($_POST['family']) ? (int)$_POST['family'] : 0,
        'mitigation_percent' => (isset($_POST['mitigation_percent']) && $_POST['mitigation_percent'] >= 0 && $_POST['mitigation_percent'] <= 100) ? (int)$_POST['mitigation_percent'] : 0
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

?>

<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); ?>
<body>

  <?php
    // Get the frameworks
    $frameworks = get_frameworks();

    // Get the total number of frameworks
    $count = count($frameworks);

    // Initialize the counter
    $counter = 1;

  ?>
  <?php
      view_top_menu("Governance");

      // Get any alert messages
      get_alert();
  ?>

  <div class="tabs new-tabs planning-tabs">
    <div class="container">

      <div class="row">

        <div class="col-3"> </div>
        <div class="col-9">
          <div class="tab-append">
            <div class="tab selected form-tab tab-show" data-content="#frameworks-tab-content"><div><span><?php echo $escaper->escapeHtml($lang['Frameworks']); ?></span></div></div>
            <div class="tab form-tab tab-show controls-tab" data-content="#controls-tab-content"><div><span><?php echo $escaper->escapehtml($lang['Controls']); ?></span></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-3">
        <?php view_governance_menu("DefineControlFrameworks"); ?>
      </div>
      <div class="col-9">
        <div id="show-alert"></div>
        <div class="row">
          <div class="col-12">
            <!--  Frameworks container Begin -->
            <div id="frameworks-tab-content" class="plan-projects tab-data hide">

              <div class="status-tabs" >

                <a href="#framework--add" id="framework-add-btn" role="button" data-toggle="modal" class="project--add"><i class="fa fa-plus"></i></a>

                <ul class="clearfix tabs-nav">
                  <li><a href="#active-frameworks" class="status" data-status="1"><?php echo $escaper->escapeHtml($lang['ActiveFrameworks']); ?> (<span id="active-frameworks-count"><?php get_frameworks_count(1) ?></span>)</a></li>
                  <li><a href="#inactive-frameworks" class="status" data-status="2"><?php echo $escaper->escapeHtml($lang['InactiveFrameworks']); ?> (<span id="inactive-frameworks-count"><?php get_frameworks_count(2) ?></span>)</a></li>
                </ul>

                  <div id="active-frameworks" class="custom-treegrid-container">
                        <?php get_framework_tabs(1) ?>
                  </div>
                  <div id="inactive-frameworks" class="custom-treegrid-container">
                        <?php get_framework_tabs(2) ?>
                  </div>
              </div> <!-- status-tabs -->

            </div>
            <!-- Frameworks container Ends -->

            <!--  Controls container Begin -->
            <div id="controls-tab-content" class="tab-data hide">
                <div class="row">
                    <div class="col-4">
                        <div class="well">
                            <h4><?php echo $escaper->escapeHtml($lang['ControlClass']); ?>:</h4>
                            <select id="filter_by_control_class" class="" multiple="multiple">
                                <?php 
                                    echo "<option selected value=\"-1\">".$escaper->escapeHtml($lang['Unassigned'])."</option>\n";
                                    $options = getAvailableControlClassList();
                                    is_array($options) || $options = array();
                                    foreach($options as $option){
                                        echo "<option selected value=\"".$escaper->escapeHtml($option['value'])."\">".$escaper->escapeHtml($option['name'])."</option>\n";
                                    } 
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="well">
                            <h4><?php echo $escaper->escapeHtml($lang['ControlPhase']); ?>:</h4>
                            <select id="filter_by_control_phase" class="" multiple="multiple">
                                <?php 
                                    echo "<option selected value=\"-1\">".$escaper->escapeHtml($lang['Unassigned'])."</option>\n";
                                    $options = getAvailableControlPhaseList();
                                    is_array($options) || $options = array();
                                    foreach($options as $option){
                                        echo "<option selected value=\"".$escaper->escapeHtml($option['value'])."\">".$escaper->escapeHtml($option['name'])."</option>\n";
                                    } 
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="well">
                            <h4><?php echo $escaper->escapeHtml($lang['ControlFamily']); ?>:</h4>
                            <select id="filter_by_control_family" class="" multiple="multiple">
                                <?php 
                                    echo "<option selected value=\"-1\">".$escaper->escapeHtml($lang['Unassigned'])."</option>\n";
                                    $options = getAvailableControlFamilyList();  
                                    is_array($options) || $options = array();
                                    foreach($options as $option){
                                        echo "<option selected value=\"".$escaper->escapeHtml($option['value'])."\">".$escaper->escapeHtml($option['name'])."</option>\n";
                                    } 
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="well">
                            <h4><?php echo $escaper->escapeHtml($lang['ControlOwner']); ?>:</h4>
                            <select id="filter_by_control_owner" class="form-field form-control" multiple="multiple">
                                <?php 
                                    echo "<option selected value=\"-1\">".$escaper->escapeHtml($lang['Unassigned'])."</option>\n";
                                    $options = getAvailableControlOwnerList();  
                                    is_array($options) || $options = array();
                                    foreach($options as $option){
                                        echo "<option selected value=\"".$escaper->escapeHtml($option['value'])."\">".$escaper->escapeHtml($option['name'])."</option>\n";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="well">
                            <h4><?php echo $escaper->escapeHtml($lang['ControlFramework']); ?>:</h4>
                            <select id="filter_by_control_framework" class="form-field form-control" multiple="multiple">
                                <?php 
                                    echo "<option selected value=\"-1\">".$escaper->escapeHtml($lang['Unassigned'])."</option>\n";
                                    $options = getAvailableControlFrameworkList();  
                                    is_array($options) || $options = array();
                                    foreach($options as $option){
                                        echo "<option selected value=\"".$escaper->escapeHtml($option['value'])."\">".$escaper->escapeHtml($option['name'])."</option>\n";
                                    } 
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="well">
                            <h4><?php echo $escaper->escapeHtml($lang['ControlPriority']); ?>:</h4>
                            <select id="filter_by_control_priority" class="form-field form-control" multiple="multiple">
                                <?php 
                                    echo "<option selected value=\"-1\">".$escaper->escapeHtml($lang['Unassigned'])."</option>\n";
                                    $options = getAvailableControlPriorityList();
                                    is_array($options) || $options = array();
                                    foreach($options as $option){
                                        echo "<option selected value=\"".$escaper->escapeHtml($option['value'])."\">".$escaper->escapeHtml($option['name'])."</option>\n";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="well">
                            <h4><?php echo $escaper->escapeHtml($lang['FilterByText']); ?>:</h4>
                            <input type="text" class="form-control" id="filter_by_control_text">
                        </div>
                    </div>
                </div>
                
              <div class="status-tabs" >
                <a href="#control--add" role="button" data-toggle="modal" class="control--add"><i class="fa fa-plus"></i></a>
                <ul class="clearfix tabs-nav">
                  <li><a href="#active-controls" class="status" data-status="1"><?php echo $escaper->escapeHtml($lang['Controls']); ?> <span id="controls_count"></span></a></li>
                </ul>
              </div> <!-- status-tabs -->

              <table id="active-controls" class="" width="100%">
                <thead style='display:none;'>
                    <tr>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            
            </div>
            <!-- Controls container Ends -->
            
          </div>
        </div>
      </div>
    </div>
  </div>
          
<!-- MODAL WINDOW FOR ADDING FRAMEWORK -->
<div id="framework--add" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="framework--add" aria-hidden="true">
  <div class="modal-body">

    <form class="" id="framework--new" action="#" method="post" autocomplete="off">
      <div class="form-group">
        <label for=""><?php echo $escaper->escapeHtml($lang['NewFrameworkName']); ?></label>
        <input type="text" required name="new_framework" id="new_framework" value="" class="form-control" autocomplete="off">
        <label for=""><?php echo $escaper->escapeHtml($lang['ParentFramework']); ?></label>
        <div class="parent_frameworks_container">
        </div>
        
        <label for=""><?php echo $escaper->escapeHtml($lang['NewFrameworkDescription']); ?></label>
        <textarea name="new_framework_description" id="new_framework_description" value="" class="form-control" rows="6" style="width:100%;"></textarea>
      </div>
      
      <div class="form-group text-right">
        <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
        <button type="submit" name="add_framework" class="btn btn-primary"><?php echo $escaper->escapeHtml($lang['Add']); ?></button>
      </div>
    </form>

  </div>
</div>

<!-- MODAL WINDOW FOR EDITING FRAMEWORK -->
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
            <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
            <button type="submit" name="update_framework" class="btn btn-primary"><?php echo $escaper->escapeHtml($lang['Update']); ?></button>
        </div>
    </form>

  </div>
</div>

<!-- MODEL WINDOW FOR PROJECT DELETE CONFIRM -->
<div id="framework--delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="framework-delete-form" aria-hidden="true">
  <div class="modal-body">

    <form class="" id="framework-delete-form" action="" method="post">
      <div class="form-group text-center">
        <label for=""><?php echo $escaper->escapeHtml($lang['AreYouSureYouWantToDeleteThisFramework']); ?></label>
        <input type="hidden" name="framework_id" value="" />
      </div>

      <div class="form-group text-center project-delete-actions">
        <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
        <button type="submit" name="delete_framework" class="delete_project btn btn-primary"><?php echo $escaper->escapeHtml($lang['Yes']); ?></button>
      </div>
    </form>

  </div>
</div>

<!-- MODEL WINDOW FOR ADDING CONTROL -->
<div id="control--add" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="control--add" aria-hidden="true">
  <div class="modal-body">

    <form class="" id="control--new" action="#controls-tab" method="post" autocomplete="off">
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
        <?php create_multiple_dropdown("frameworks", NULL, "add_framework_ids"); ?>

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

        <label for=""><?php echo $escaper->escapeHtml($lang['MitigationPercent']); ?></label>
        <input type="number" min="0" max="100" name="mitigation_percent" value="" class="form-control">
      </div>
      
      <div class="form-group text-right">
        <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
        <button type="submit" name="add_control" class="btn btn-primary"><?php echo $escaper->escapeHtml($lang['Add']); ?></button>
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

        <label for=""><?php echo $escaper->escapeHtml($lang['MitigationPercent']); ?></label>
        <input type="number" min="0" max="100" name="mitigation_percent" value="" class="form-control">
      </div>
      
      <div class="form-group text-right">
        <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
        <button type="submit" name="update_control" class="btn btn-primary"><?php echo $escaper->escapeHtml($lang['Update']); ?></button>
      </div>
    </form>

  </div>
</div>

<!-- MODEL WINDOW FOR CONTROL DELETE CONFIRM -->
<div id="control--delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="control--delete" aria-hidden="true">
  <div class="modal-body">

    <form class="" id="control--delete" action="" method="post">
      <div class="form-group text-center">
        <label for=""><?php echo $escaper->escapeHtml($lang['AreYouSureYouWantToDeleteThisControl']); ?></label>
        <input type="hidden" name="control_id" value="" />
      </div>

      <div class="form-group text-center control-delete-actions">
        <button class="btn btn-secondary" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
        <button type="submit" name="delete_control" class="delete_control btn btn-primary"><?php echo $escaper->escapeHtml($lang['Yes']); ?></button>
      </div>
    </form>

  </div>
</div>

    <?php display_set_default_date_format_script(); ?>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>
</body>
</html>