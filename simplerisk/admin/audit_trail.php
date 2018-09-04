<?php
        /* This Source Code Form is subject to the terms of the Mozilla Public
         * License, v. 2.0. If a copy of the MPL was not distributed with this
         * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

        // Include required functions file
        require_once(realpath(__DIR__ . '/../includes/functions.php'));
        require_once(realpath(__DIR__ . '/../includes/authenticate.php'));
	require_once(realpath(__DIR__ . '/../includes/display.php'));

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

	// If the days value is post
	if (isset($_POST['days']))
	{
		$days = (int)$_POST['days'];
	}
	// Otherwise use a week
	else $days = 7;
?>

<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); ?>
<body>
    <?php view_top_menu("Configure"); ?>
    <div class="container">
      <div class="row">
        <div class="col-3">
          <?php view_configure_menu("AuditTrail"); ?>
        </div>
        <div class="col-9">
          <div class="row">
            <div class="well">
              <h4><?php echo $escaper->escapeHtml($lang['AuditTrail']); ?></h4>
              <form name="change_timeframe" method="post" action="">
              <select name="days" id="days" onchange="javascript: submit()">
                <option value="7"<?php echo ($days == 7) ? " selected" : ""; ?>>Past Week</option>
                <option value="30"<?php echo ($days == 30) ? " selected" : ""; ?>>Past Month</option>
                <option value="90"<?php echo ($days == 90) ? " selected" : ""; ?>>Past Quarter</option>
                <option value="180"<?php echo ($days == 180) ? " selected" : ""; ?>>Past 6 Months</option>
                <option value="365"<?php echo ($days == 365) ? " selected" : ""; ?>>Past Year</option>
                <option value="36500"<?php echo ($days == 36500) ? " selected" : ""; ?>>All Time</option>
              </select>
              </form>
              <?php get_audit_trail(NULL, $days); ?>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>
</body>
</html>