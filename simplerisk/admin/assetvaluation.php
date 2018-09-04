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

	// Check if the default asset valuation was submitted
	if (isset($_POST['update_default_value']))
	{
		// If the currency is set and is not empty
		if (isset($_POST['currency']) && ($_POST['currency'] != ""))
		{
			// If the currency value is one character long
			if (strlen($_POST['currency']) <= 6)
			{
				// Update the currency
				update_setting("currency", $_POST['currency']);
			}
		}

		// If value is set and is numeric
		if (isset($_POST['value']) && is_numeric($_POST['value']))
		{
			$value = (int)$_POST['value'];

			// If the value is between 1 and 10
			if ($value >= 1 && $value <= 10)
			{
				// Update the default asset valuation
				update_default_asset_valuation($value);
			}
		}
	}

	// Check if the automatic asset valuation was submitted
	if (isset($_POST['update_auto_value']))
	{
		$min_value = $_POST['min_value'];
		$max_value = $_POST['max_value'];

		// If the minimum value is an integer >= 0
		if (is_numeric($min_value) && $min_value >= 0)
		{
			// If the maximum value is an integer
			if (is_numeric($max_value))
			{
				// Update the asset values
				$success = update_asset_values($min_value, $max_value);

				// If the update was successful
				if ($success)
				{
					// Display an alert
					set_alert(true, "good", "The asset valuation settings were updated successfully.");
				}
				else
				{
					// Display an alert
					set_alert(true, "bad", "There was an issue updating the asset valuation settings.");
				}
			}
			else
			{
				// Display an alert
				set_alert(true, "bad", "Please specify an integer for the maximum value.");
			}
		}
		else
		{
			// Display an alert
			set_alert(true, "bad", "Please specify an integer greater than or equal to zero for the minimum value.");
		}
	}

	// Check if the manual asset valuation was submitted
	if (isset($_POST['update_manual_value']))
	{
		// For each value range
		for ($i=1; $i<=10; $i++)
		{
			$id = $i;
			$min_name = "min_value_" . $i;
			$min_value = $_POST[$min_name];
			$max_name = "max_value_" . $i;
			$max_value = $_POST[$max_name];

			// If the min_value and max_value are numeric
			if (is_numeric($min_value) && is_numeric($max_value))
			{
				// Update the asset value
				$success = update_asset_value($id, $min_value, $max_value);

                                // If the update was successful
                                if ($success)
                                {
					// Display an alert
					set_alert(true, "good", "The asset valuation settings were updated successfully.");
                                }
                                else
                                {
					// Display an alert
					set_alert(true, "bad", "There was an issue updating the asset valuation settings.");
                                }
			}
		}
	}

?>

<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); ?>
<body>

<?php
	view_top_menu("Configure");

	// Get any alert messages
	get_alert();
?>
    <div class="container">
      <div class="row">
        <div class="col-3">
          <?php view_configure_menu("AssetValuation"); ?>
        </div>
        <div class="col-9">
          <div class="row">
            <div class="col-12">
              <div class="hero-unit">
                <form name="automatic" method="post" action="">
                <h4><?php echo $escaper->escapeHtml($lang['AutomaticAssetValuation']); ?>:</h4>
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
                  <td><?php echo $escaper->escapeHtml($lang['MinimumValue']); ?>:&nbsp;</td>
		  <td><input id="dollarsign" type="number" name="min_value" min="0" size="20" value="<?php echo asset_min_value(); ?>" /></td>
                </tr>
                <tr>
		  <td><?php echo $escaper->escapeHtml($lang['MaximumValue']); ?>:&nbsp;</td>
		  <td><input id="dollarsign" type="number" name="max_value" size="20" value="<?php echo asset_max_value(); ?>" /></td>
		</tr>
		<tr>
		  <td colspan="2"><input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_auto_value" /></td>
		</tr>
		</table>
                </form>
              </div>
              <div class="hero-unit">
                <form name="manual" method="post" action="">
                <p>
                <h4><?php echo $escaper->escapeHtml($lang['ManualAssetValuation']); ?>:</h4>
		<input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_manual_value" />
		<?php display_asset_valuation_table(); ?>
		<input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_manual_value" />
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>
</body>
</html>