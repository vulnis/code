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
                <?php view_compliance_menu("PastAudits"); ?>
            </div>
            <div class="col-9 compliance-content-container content-margin-height">
                <div id="show-alert"></div>
                <div class="row">
                    <div class="col-12">
                        <?php display_past_audits(); ?>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
    <?php display_set_default_date_format_script(); ?>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>
</body>
</html>
