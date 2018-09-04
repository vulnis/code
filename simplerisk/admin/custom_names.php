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

        // Check if the impact update was submitted
        if (isset($_POST['update_impact']))
        {
                $new_name = $_POST['new_name'];
                $value = (int)$_POST['impact'];

                // Verify value is an integer
                if (is_int($value))
                {
                        update_table("impact", $new_name, $value);

			// Display an alert
			set_alert(true, "good", "The impact naming convention was updated successfully.");
                }
        }

        // Check if the likelihood update was submitted
        if (isset($_POST['update_likelihood']))
        {
                $new_name = $_POST['new_name'];
                $value = (int)$_POST['likelihood'];

                // Verify value is an integer
                if (is_int($value))
                {
                        update_table("likelihood", $new_name, $value);

			// Display an alert
			set_alert(true, "good", "The likelihood naming convention was updated successfully.");
                }
        }

        // Check if the mitigation effort update was submitted
        if (isset($_POST['update_mitigation_effort']))
        {
                $new_name = $_POST['new_name'];
                $value = (int)$_POST['mitigation_effort'];

                // Verify value is an integer
                if (is_int($value))
                {
                        update_table("mitigation_effort", $new_name, $value);

			// Display an alert
			set_alert(true, "good", "The mitigation effort naming convention was updated successfully.");
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
        <?php view_configure_menu("RedefineNamingConventions"); ?>
        </div>
        <div class="col-9">
            <form name="impact" method="post" action="">
                <h4><?php echo $escaper->escapeHtml($lang['Impact']); ?>:</h4>
                <?php echo $escaper->escapeHtml($lang['Change']); ?> <?php create_dropdown("impact") ?> <?php echo $escaper->escapeHtml($lang['to']); ?> <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_impact" />
            </form>
            <form name="likelihood" method="post" action="">
                <h4><?php echo $escaper->escapeHtml($lang['Likelihood']); ?>:</h4>
                <?php echo $escaper->escapeHtml($lang['Change']); ?> <?php create_dropdown("likelihood") ?> <?php echo $escaper->escapeHtml($lang['to']); ?> <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_likelihood" />
            </form>
            <form name="mitigation_effort" method="post" action="">
                <h4><?php echo $escaper->escapeHtml($lang['MitigationEffort']); ?>:</h4>
                <?php echo $escaper->escapeHtml($lang['Change']); ?> <?php create_dropdown("mitigation_effort") ?> <?php echo $escaper->escapeHtml($lang['to']); ?> <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_mitigation_effort" />
            </form>
        </div>
    </div>
</div>
<?php 
    include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); 
?>