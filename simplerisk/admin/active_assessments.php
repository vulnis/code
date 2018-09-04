<?php
    /* This Source Code Form is subject to the terms of the Mozilla Public
     * License, v. 2.0. If a copy of the MPL was not distributed with this
     * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

    // Include required functions file
    require_once(realpath(__DIR__ . '/../includes/functions.php'));
    require_once(realpath(__DIR__ . '/../includes/authenticate.php'));
    require_once(realpath(__DIR__ . '/../includes/display.php'));
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
    if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != "1")
    {
        header("Location: ../index.php");
        exit(0);
    }

    // Check if assessment extra is enabled
    if(assessments_extra())
    {
        // Include the assessments extra
        require_once(realpath(__DIR__ . '/../extras/assessments/index.php'));
    }
    else
    {
        header("Location: ../index.php");
        exit(0);
    }
    
    // If we should delete an active assessment
    if (isset($_POST['delete_active_assessments']))
    {
        // Get the selected assessments
        $tokens = $_POST['tokens'];

        // Delete the assessments
        delete_active_questionnaires($tokens);

        // Display an alert
        set_alert(true, "good", "The assessment(s) were deleted successfully.");
        
        refresh();
    }
?>

<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); ?>
    <script type="text/javascript">
      
    </script>

<?php
    view_top_menu("Configure");

        // Get any alert messages
        get_alert();
?>
    <div class="container">
      <div class="row">
        <div class="col-3">
          <?php view_configure_menu("ActiveAssessments"); ?>
        </div>
        <div class="col-9">
          <div class="row">
            <div class="col-12">
              <div class="hero-unit">
                <?php display_active_assessments(); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>