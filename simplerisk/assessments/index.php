<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
* License, v. 2.0. If a copy of the MPL was not distributed with this
* file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Include required functions file
require_once(realpath(__DIR__ . '/../includes/functions.php'));
require_once(realpath(__DIR__ . '/../includes/authenticate.php'));
require_once(realpath(__DIR__ . '/../includes/display.php'));
require_once(realpath(__DIR__ . '/../includes/assessments.php'));
require_once(realpath(__DIR__ . '/../includes/alerts.php'));

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

require_once(realpath(__DIR__ . '/../includes/csrf-magic/csrf-magic.php'));

// Check for session timeout or renegotiation
session_check();

// Check if access is authorized
if (!isset($_SESSION["access"]) || $_SESSION["access"] != "granted")
{
  set_unauthenticated_redirect();
  header("Location: ../index.php");
  exit(0);
}

// Check if access is authorized
if (!isset($_SESSION["assessments"]) || $_SESSION["assessments"] != "1")
{
  header("Location: ../index.php");
  exit(0);
}

// If an assessment was posted
if (isset($_POST['action']) && $_POST['action'] == "submit")
{
  // Process the assessment
  process_assessment();
}

// If an assessment was sent
if (isset($_POST['send_assessment']))
{
  // If the assessments extra is enabled
  if (assessments_extra())
  {
    // Include the assessments extra
    require_once(realpath(__DIR__ . '/../extras/assessments/index.php'));

    // Process the sent assessment
    process_sent_assessment();
  }
}

// If an action was sent
if (isset($_GET['action']))
{
  // If the action is create
  if ($_GET['action'] == "create")
  {
    // Use the Create Assessments menu
    $menu = "CreateAssessment";
  }
  // If the action is edit
  else if ($_GET['action'] == "edit")
  {
    // Use the Edit Assessments menu
    $menu = "EditAssessment";
  }
  // If the action is view
  else if ($_GET['action'] == "view")
  {
    // Use the Available Assessments menu
    $menu = "AvailableAssessments";
  }
  // If the action is send
  else if ($_GET['action'] == "send")
  {
    // Use the Send Assessments menu
    $menu = "SendAssessment";
  }
}
// Otherwise
else
{
  // Use the Available Assessments menu
  $menu = "AvailableAssessments";
}

?>

<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); ?>
<body>
  <?php
      view_top_menu("Assessments");

      // Get any alert messages
      get_alert();
  ?>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span3">
        <?php view_assessments_menu($menu); ?>
      </div>
      <div class="span9">
        <div id="show-alert">
            <?php  
                // Get any alert messages
                get_alert();
            ?>
        </div>

        <?php
        // If the action was create
        if ((isset($_GET['action']) && $_GET['action'] == "create") || (isset($_POST['action']) && $_POST['action'] == "create"))
        {
          // If the assessments extra is enabled
          if (assessments_extra())
          {
            // Include the assessments extra
            require_once(realpath(__DIR__ . '/../extras/assessments/index.php'));

            // Display the create assessments
            display_create_assessments();
          }
        }
        // If the action was edit
        else if ((isset($_GET['action']) && $_GET['action'] == "edit") || (isset($_POST['action']) && $_POST['action'] == "edit"))
        {
          // If the assessments extra is enabled
          if (assessments_extra())
          {
            // Include the assessments extra
            require_once(realpath(__DIR__ . '/../extras/assessments/index.php'));
            
            // Display the edit assessments
            echo "<div id=\"edit-assessment-container\">";
            display_edit_assessments();
            echo "</div>";
          }
        }
        // If the action was view
        else if ((isset($_GET['action']) && $_GET['action'] == "view") || (isset($_POST['action']) && $_POST['action'] == "view"))
        {
          // Display the assessment questions
          display_view_assessment_questions();
        }
        // If the action was send
        else if ((isset($_GET['action']) && $_GET['action'] == "send") || (isset($_POST['action']) && $_POST['action'] == "send"))
        {
          // If the assessments extra is enabled
          if (assessments_extra())
          {
            // Include the assessments extra
            require_once(realpath(__DIR__ . '/../extras/assessments/index.php'));

            // Display the send assessment options
            display_send_assessment_options();
          }
        }
        else
        {
          // Display the available assessments
          display_assessment_links();
        }
        ?>
      </div>
    </div>
  </div>
    <?php display_set_default_date_format_script(); ?>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>
</body>
</html>