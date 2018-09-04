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

    // If the extra directory exists
    if (is_dir(realpath(__DIR__ . '/../extras/complianceforge')))
    {
        // Include the ComplianceForge Extra
        require_once(realpath(__DIR__ . '/../extras/complianceforge/index.php'));

        // If the user wants to activate the extra
        if (isset($_POST['activate']))
        {
            // Enable the ComplianceForge Extra
            enable_complianceforge_extra();
        }

        // If the user wants to deactivate the extra
        if (isset($_POST['deactivate']))
        {
            // Disable the ComplianceForge Extra
            disable_complianceforge_extra();
        }
    }

/*********************
 * FUNCTION: DISPLAY *
 *********************/
function display()                                    
{
    global $lang;
    global $escaper;

    // If the extra directory exists
    if (is_dir(realpath(__DIR__ . '/../extras/complianceforge')))
    {
        // But the extra is not activated
        if (!complianceforge_extra())
        {
            // If the extra is not restricted based on the install type
            if (!restricted_extra("complianceforgescf"))
            {
                echo "<form name=\"activate\" method=\"post\" action=\"\">\n";
                echo "<input type=\"submit\" value=\"" . $escaper->escapeHtml($lang['Activate']) . "\" name=\"activate\" /><br />\n";
                echo "</form>\n";
            }
            // The extra is restricted
            else echo $escaper->escapeHtml($lang['YouNeedToUpgradeYourSimpleRiskSubscription']);
        }
        // Once it has been activated
        else
        {
            // Include the Assessments Extra
            require_once(realpath(__DIR__ . '/../extras/complianceforge/index.php'));

            display_complianceforge();
        }
    }
    // Otherwise, the Extra does not exist
    else
    {
        echo "<a href=\"https://www.simplerisk.com/extras\" target=\"_blank\">Purchase the Extra</a>\n";
    }
}

?>
<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php 
    include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); 
    view_top_menu("Configure");
    get_alert();
?>
<div class="container">
    <div class="row">
        <div class="col-3">
            <?php view_configure_menu("Extras"); ?>
        </div>
        <div class="col-9">
            <div class="row">
                <div class="col-12">
                    <h4>ComplianceForge DSP Extra</h4>
                    <?php display(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
    
<?php 
    display_set_default_date_format_script();
    include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); 
?>
