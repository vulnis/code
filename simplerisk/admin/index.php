<?php
    /* This Source Code Form is subject to the terms of the Mozilla Public
     * License, v. 2.0. If a copy of the MPL was not distributed with this
     * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

    // Include required functions file
    require_once(realpath(__DIR__ . '/../includes/functions.php'));
    require_once(realpath(__DIR__ . '/../includes/authenticate.php'));
    require_once(realpath(__DIR__ . '/../includes/display.php'));
    require_once(realpath(__DIR__ . '/../includes/mail.php'));
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

	// If the General tab was submitted
	if (isset($_POST['update_general_settings']))
	{
	        // Set the error to false
	        $error = false;

		// Update the setting to enable pop-up windows for text boxes
		$enable_popup = (isset($_POST['enable_popup'])) ? 1 : 0;
		$current_enable_popup = get_setting("enable_popup");
		if ($enable_popup != $current_enable_popup)
		{
			update_setting("enable_popup", $enable_popup);
		}

		// Update the setting to show all risks for plan projects
		$plan_projects_show_all = (isset($_POST['plan_projects_show_all'])) ? 1 : 0;
		$current_plan_projects_show_all = get_setting("plan_projects_show_all");
		if ($plan_projects_show_all != $current_plan_projects_show_all)
		{
			update_setting("plan_projects_show_all", $plan_projects_show_all);
		}

		// Update the default language setting
		$default_language = get_name_by_value("languages", (int)$_POST['languages']);
		$current_default_language = get_setting("default_language");
		if ($default_language != $current_default_language)
		{
			update_setting("default_language", $default_language);
		}

		// Update the default timezone setting
		$default_timezone = $_POST['default_timezone'];
		$current_default_timezone = get_setting("default_timezone");
		if ($default_timezone != $current_default_timezone)
		{
			update_setting("default_timezone", $default_timezone);
		}

		// Update the default date format setting
		$default_date_format = $_POST['default_date_format'];
		$current_default_date_format = get_setting("default_date_format");
		if ($default_date_format != $current_default_date_format)
		{
			update_setting("default_date_format", $default_date_format);
		}

		// Update the default risk score setting
		$default_risk_score = $_POST['default_risk_score'];
		$current_default_risk_score = get_setting("default_risk_score");
		if ($default_risk_score != $current_default_risk_score)
		{
			// If the default risk score is between 0 and 10
			if (($default_risk_score >= 0) && ($default_risk_score <= 10))
			{
				update_setting("default_risk_score", $default_risk_score);
			}
		}
        
		// Update the default closed audit status setting
		$default_closed_audit_status = (int)$_POST['closed_audit_status'];
		$current_default_closed_audit_status = get_setting("closed_audit_status");
		if ($default_closed_audit_status != $current_default_closed_audit_status)
		{
			// If the default closed audit status is empty
			if (empty($default_closed_audit_status))
			{
				set_alert(true, "bad", $escaper->escapeHtml($lang['ClosedAuditStatusIsRequired']));
				$error = true;
			}
			else
			{
				update_setting("closed_audit_status", $default_closed_audit_status);
			}
		}

		// Update the default initiated audit status setting
		$default_initiated_audit_status = (int)$_POST['initiated_audit_status'];
		$current_default_initiated_audit_status = get_setting("initiated_audit_status");
		if ($default_initiated_audit_status != $current_default_initiated_audit_status)
		{
			update_setting("initiated_audit_status", $default_initiated_audit_status);
		}

		// Update the default currency setting
		$default_currency = $_POST['default_currency'];
		$current_default_currency = get_setting("currency");
		if ($default_currency != $current_default_currency)
		{
			// If the default currency is not empty
			if ($default_currency != "")
			{
				// If the default currency value is less than or equal to six characters long
				if (strlen($default_currency) <= 6)
				{
					// Update the currency
					update_setting("currency", $default_currency);
				}
			}
		}

		// Update the default asset valuation setting
		$default_asset_valuation = (int)$_POST['default_asset_valuation'];
		$current_default_asset_valuation = get_setting("default_asset_valuation");
		if ($default_asset_valuation != $current_default_asset_valuation)
		{
			// If the default asset valuation is numeric
			if (is_numeric($default_asset_valuation))
			{
				// If the default asset valuation is between 1 and 10
				if ($default_asset_valuation >= 1 && $default_asset_valuation <= 10)
				{
					// Update the default asset valuation
					update_setting("default_asset_valuation", $default_asset_valuation);
				}
			}
		}

		// Update the default user role setting
		$default_user_role = (int)$_POST['default_user_role'];
		$current_default_user_role = get_setting("default_user_role");
		if ($default_user_role != $current_default_user_role)
		{
			// Update the default user role
			update_setting("default_user_role", $default_user_role);
		}
        
		// Update the next review date setting
		$next_review_date_uses = $_POST['next_review_date_uses'];
		$current_next_review_date_uses = get_setting("next_review_date_uses");
		if ($next_review_date_uses != $current_next_review_date_uses)
		{
			// Update the default user role
			update_setting("next_review_date_uses", $next_review_date_uses);

			// Update all next review dates based on next_review_date_uses setting 
			update_all_next_review_dates();
		}

        	// If all setting values were saved successfully
        	if (!$error)
        	{
                	// Display an alert
                	set_alert(true, "good", "The settings were updated successfully.");
        	}
	}

	// Check if a new file type was submitted
	if (isset($_POST['add_file_type']))
	{
		$name = $_POST['new_file_type'];

		// Insert a new file type up to 100 chars
		add_name("file_types", $name, 100);

		// Display an alert
		set_alert(true, "good", "A new upload file type was added successfully.");
	}

	// Check if a file type was deleted
	if (isset($_POST['delete_file_type']))
	{
       		$value = (int)$_POST['file_types'];

                // Verify value is an integer
                if (is_int($value))
                {
                       	delete_value("file_types", $value);

                       	// Display an alert
                       	set_alert(true, "good", "An existing upload file type was removed successfully.");
                }
        }

        // Check if the maximum file upload size was updated
        if (isset($_POST['update_max_upload_size']))
        {
               	// Verify value is a numeric value
               	if (is_numeric($_POST['size']))
               	{
                       	update_setting('max_upload_size', $_POST['size']);

                       	// Get the currently set max upload size for SimpleRisk
                       	$simplerisk_max_upload_size = get_setting('max_upload_size');

                       	// If the max upload size for SimpleRisk is bigger than the PHP max upload size
                       	if ($simplerisk_max_upload_size > php_max_allowed_values())
                       	{
                               	// Display an alert
                               	set_alert(true, "bad", $escaper->escapeHtml($lang['WarnPHPUploadSize']));
                       	}
                       	// If the max upload size for SimpleRisk is bigger than the MySQL max_allowed_packet
                       	else if ($simplerisk_max_upload_size > mysql_max_allowed_values())
                       	{
                               	// Display an alert
                               	set_alert(true, "bad", $escaper->escapeHtml($lang['WarnMySQLUploadSize']));
                       	}
                       	else
                       	{
                       	        // Display an alert
                       	        set_alert(true, "good", "The maximum upload file size was updated successfully.");
                       	}
               	}
               	else
               	{
                       	// Display an alert
                       	set_alert(true, "bad", "The maximum upload file size needs to be an integer value.");
               	}
        }

        // Check if the mail settings were submitted
        if (isset($_POST['submit_mail']))
        {
                // Get the posted values
                $transport = $_POST['transport'];
                $from_email = $_POST['from_email'];
                $from_name = $_POST['from_name'];
                $replyto_email = $_POST['replyto_email'];
                $replyto_name = $_POST['replyto_name'];
                $host = $_POST['host'];
                $smtpautotls = (isset($_POST['smtpautotls'])) ? "true" : "false";
                $smtpauth = (isset($_POST['smtpauth'])) ? "true" : "false";
                $username = $_POST['username'];
                $password = $_POST['password'];
                $encryption = $_POST['encryption'];
                $port = $_POST['port'];

                // Update the mail settings
                update_mail_settings($transport, $from_email, $from_name, $replyto_email, $replyto_name, $host, $smtpautotls, $smtpauth, $username, $password, $encryption, $port);

                // Display an alert
                set_alert(true, "good", "Mail settings were updated successfully.");
        }

        // Check if the mail test was submitted
        if (isset($_POST['test_mail_configuration']))
        {
                // Set up the test email
                $name = "SimpleRisk Test";
                $email = $_POST['email'];
                $subject = "SimpleRisk Test Email";
                $full_message = "This is a test email from SimpleRisk.";

                // Send the e-mail
                send_email($name, $email, $subject, $full_message);

                // Display an alert
                set_alert(true, "good", "A test email has been sent using the current settings.");
        }
        
        // If the Security tab was submitted
        if (isset($_POST['update_security_settings']))
        {
                // Set the error to false
                $error = false;

		// Update the session activity timeout setting
		$session_activity_timeout = (int)$_POST['session_activity_timeout'];
		$current_session_activity_timeout = get_setting("session_activity_timeout");
		if ($session_activity_timeout != $current_session_activity_timeout)
		{
			update_setting("session_activity_timeout", $session_activity_timeout);
		}

		// Update the session renegotiation period setting
		$session_renegotiation_period = (int)$_POST['session_renegotiation_period'];
		$current_session_renegotiation_period = get_setting("session_renegotiation_period");
		if ($session_renegotiation_period != $current_session_renegotiation_period)
		{
			update_setting("session_renegotiation_period", $session_renegotiation_period);
		}

		// Update the content security policy
		$content_security_policy = isset($_POST['content_security_policy']) ? 1 : 0;
		$current_content_security_policy = get_setting("content_security_policy");
		if ($content_security_policy != $current_content_security_policy)
		{
			update_setting("content_security_policy", $content_security_policy);
		}

                // If all setting values were saved successfully
                if (!$error)
                {
                        // Display an alert
                        set_alert(true, "good", "The settings were updated successfully.");
                }
	}

        // If the Debug tab was submitted
        if (isset($_POST['update_debug_settings']))
        {
                // Set the error to false
                $error = false;

		// Update the debug logging
		$debug_logging = isset($_POST['debug_logging']) ? 1 : 0;
		$current_debug_logging = get_setting("debug_logging");
		if ($debug_logging != $current_debug_logging)
		{
			update_setting("debug_logging", $debug_logging);
		}

		// Update the debug log file
		$debug_log_file = $_POST['debug_log_file'];
		$current_debug_log_file = get_setting("debug_log_file");
		if ($debug_log_file != $current_debug_log_file)
		{
			update_setting("debug_log_file", $debug_log_file);
		}

                // If all setting values were saved successfully
                if (!$error)
                {
                        // Display an alert
                        set_alert(true, "good", "The settings were updated successfully.");
                }
	}

	// Get the max upload size setting
	$simplerisk_max_upload_size = get_setting('max_upload_size');
?>

<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); ?>
<body>
  <script>
      function dropdown_transport()
      {
        smtp = document.getElementsByClassName("smtp");
        smtpauth = document.getElementsByClassName("smtpauth");
        dropdown = document.getElementById("transport");
        if(dropdown.options[dropdown.selectedIndex].text == "smtp")
        {
          for(i=0; i<smtp.length; i++)
          {
            smtp[i].style.display = "";
          }

          checkbox = document.getElementById("smtpauth");
          if(checkbox.checked)
          {
            for(i=0; i<smtpauth.length; i++)
            {
              smtpauth[i].style.display = "";
            }
          }
        }
        else
        {
          for(i=0; i<smtp.length; i++)
          {
            smtp[i].style.display = "none";
          }

          for(i=0; i<smtpauth.length; i++)
          {
            smtpauth[i].style.display = "none";
          }
        }
      }

      function checkbox_smtpauth()
      {
        elements = document.getElementsByClassName("smtpauth");
        checkbox = document.getElementById("smtpauth");
        if(checkbox.checked)
        {
          for(i=0; i<elements.length; i++)
          {
            elements[i].style.display = "";
          }
        }
        else
        {
          for(i=0; i<elements.length; i++)
          {
            elements[i].style.display = "none";
          }
        }
      }
    </script>
<?php
    view_top_menu("Configure");

    // Get any alert messages
    get_alert();
?>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <?php view_configure_menu("Settings"); ?>
        </div>
        <div class="span9">
          <div class="wrap">
            <ul class="tabs group">
              <li><a class="active" href="#/general"><?php echo $escaper->escapeHtml($lang['General']); ?></a></li>
              <li><a href="#/files"><?php echo $escaper->escapeHtml($lang['FileUpload']); ?></a></li>
              <li><a href="#/mail"><?php echo $escaper->escapeHtml($lang['Mail']); ?></a></li>
              <li><a href="#/security"><?php echo $escaper->escapeHtml($lang['Security']); ?></a></li>
              <li><a href="#/debug"><?php echo $escaper->escapeHtml($lang['Debugging']); ?></a></li>
              <!--<li><a href="#/extras">Extras</a></li>-->
            </ul>
            <div id="content">
              <form name="settings" method="post" action="">

              <!-- General Setting Tab -->
              <div id="general">
                <form name="general_settings" method="post" action="">
                  <table border="1" width="600" cellpadding="10px">
                    <tbody>
                    <tr>
                      <td>
                        <table border="0" width="100%">
                          <tbody>
                            <tr>
                              <td colspan="2"><u><strong><?php echo $escaper->escapeHtml($lang['UserInterface']); ?></strong></u></td>
                            </tr>
                            <tr>
                              <td colspan="2"><input <?php if(get_setting('enable_popup') == 1){ echo "checked"; } ?> name="enable_popup" class="hidden-checkbox" size="2" value="90" id="enable_popup" type="checkbox">  <label for="enable_popup"  >&nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['EnablePopupWindowsForTextBoxes']); ?></label></td>
                            </tr>
                            <tr>
                              <td colspan="2"><input <?php if(get_setting('plan_projects_show_all') == 1){ echo "checked"; } ?> name="plan_projects_show_all" class="hidden-checkbox" size="2" value="90" id="plan_projects_show_all" type="checkbox">  <label for="plan_projects_show_all"  >&nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['ShowAllRisksForPlanProjects']); ?></label></td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                    <br />
                    <table border="1" width="600" cellpadding="10px">
                    <tbody>
                    <tr>
                      <td>
                        <table border="0" width="100%">
                          <tbody>
                            <tr>
                              <td colspan="2"><u><strong><?php echo $escaper->escapeHtml($lang['DefaultValues']); ?></strong></u></td>
                            </tr>
                            <tr>
                              <td width="300px"><?php echo $escaper->escapeHtml($lang['DefaultLanguage']); ?>:</td>
                              <td><?php create_dropdown("languages", get_value_by_name("languages", get_setting("default_language")), null, false); ?></td>
                            </tr>
                            <tr>
                              <td width="300px"><?php echo $escaper->escapeHtml($lang['DefaultTimezone']); ?>:</td>
                              <td>
                                <?php
                                    echo "<select id=\"default_timezone\" name=\"default_timezone\" class=\"form-field\" style=\"width:auto;\">\n";

                                    // Get the list of timezones
                                    $timezones = timezone_list();

                                    // Get the defeault timezone
                                    $default_timezone = get_setting("default_timezone");

                                    // For each timezone
                                    foreach($timezones as $key => $value)
                                    {
                                        echo "<option value=\"" . $key . "\"" . ($key == $default_timezone ? " selected" : "") . ">" . $value . "</option>\n";
                                    }

                                    echo "</select>\n";
                                ?>
                              </td>
                            </tr>
                            <tr>
                              <td width="300px"><?php echo $escaper->escapeHtml($lang['DefaultDateFormat']); ?>:</td>
                              <td>
                                <?php
                                    // Get the defeault date format
                                    $default_date_format = get_setting("default_date_format");

                                    create_dropdown("date_formats", $default_date_format, "default_date_format", false);

                                    echo "</select>\n";
                                ?>
                              </td>
                            </tr>
                            <tr>
                              <td width="300px"><?php echo $escaper->escapeHtml($lang['DefaultRiskScore']); ?>:</td>
                              <td><input value="<?php echo (get_setting('default_risk_score') ? get_setting('default_risk_score') : 10); ?>" name="default_risk_score" id="default_risk_score" type="number" min="0" step="0.1" max="10" /></td>
                            </tr>
                            <tr>
                              <td><?php echo $escaper->escapeHtml($lang['DefaultInitiatedAuditStatus']) ?>:</td>
                              <td><?php create_dropdown("test_status", get_setting("initiated_audit_status"), "initiated_audit_status", true, false, false, "", "--", 0); ?></td>
                            </tr>
                            <tr>
                              <td><?php echo $escaper->escapeHtml($lang['DefaultClosedAuditStatus']) ?>:</td>
                              <td><?php create_dropdown("test_status", get_setting("closed_audit_status"), "closed_audit_status", false, false, false, "required"); ?></td>
                            </tr>
                            <tr>
                              <td><?php echo $escaper->escapeHtml($lang['DefaultCurrencySymbol']) ?>:</td>
                              <td><input type="text" name="default_currency" maxlength="3" value="<?php echo get_setting("currency"); ?>" /></td>
                            </tr>
                            <tr>
                              <td><?php echo $escaper->escapeHtml($lang['DefaultAssetValuation']) ?>:</td>
                              <td>
                                <?php
                                  // Get the default asset valuation
                                  $default = get_default_asset_valuation();

                                  // Create the asset valuation dropdown
                                  create_asset_valuation_dropdown("default_asset_valuation", $default);
                                ?>
                              </td>
                            </tr>
                            <tr>
                              <td><?php echo $escaper->escapeHtml($lang['DefaultUserRole']) ?>:</td>
                              <td>
                                <?php
                                  // Create role dropdown
                                  create_dropdown("role", get_setting("default_user_role"), "default_user_role");
                                ?>
                              </td>
                            </tr>
                            <tr>
                              <td><?php echo $escaper->escapeHtml($lang['NextReviewDateUses']) ?>:</td>
                              <td>
                                <select name="next_review_date_uses">
                                    <option value="InherentRisk" <?php echo get_setting("next_review_date_uses") == "InherentRisk" ? "selected" : ""; ?> ><?php echo $escaper->escapeHtml($lang['InherentRisk']); ?></option>
                                    <option value="ResidualRisk" <?php echo get_setting("next_review_date_uses") == "ResidualRisk" ? "selected" : ""; ?>><?php echo $escaper->escapeHtml($lang['ResidualRisk']); ?></option>
                                </select>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    </tbody>
                    </table>
                    <br />
                    <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_general_settings" />
                </form>
              </div>

              <!-- File Upload Setting Tab -->
              <div id="files" style="display: none;">
                    <table border="1" width="600" cellpadding="10px">
                    <tbody>
                    <tr>
                      <td>
                        <form name="filetypes" method="post" action="">
                        <table border="0" width="100%">
                          <tbody>
                            <tr>
                              <td><h4><?php echo $escaper->escapeHtml($lang['AllowedFileTypes']); ?>:</h4></td>
                            </tr>
                            <tr>
                              <td><?php echo $escaper->escapeHtml($lang['AddNewFileTypeOf']); ?> <input name="new_file_type" type="text" maxlength="50" size="20" />&nbsp;&nbsp;<input type="submit" value="<?php echo $escaper->escapeHtml($lang['Add']); ?>" name="add_file_type" /></td>
                            </tr>
                            <tr>
                              <td><?php echo $escaper->escapeHtml($lang['DeleteCurrentFileTypeOf']); ?> <?php create_dropdown("file_types"); ?>&nbsp;&nbsp;<input type="submit" value="<?php echo $escaper->escapeHtml($lang['Delete']); ?>" name="delete_file_type" /></td>
                            </tr>
                          </tbody>
                        </table>
                        </form>
                      </td>
                    </tr>
                    </tbody>
                    </table>
                    <br />
                    <table border="1" width="600" cellpadding="10px">
                    <tbody>
                    <tr>
                      <td>
                        <form name="filesize" method="post" action="">
                        <table border="0" width="100%">
                          <tbody>
                            <tr>
                              <td><h4><?php echo $escaper->escapeHtml($lang['MaximumUploadFileSize']); ?>:</h4></td>
                            </tr>
                            <tr>
                              <td><input name="size" type="number" maxlength="50" size="20" value="<?php echo get_setting('max_upload_size'); ?>" />&nbsp;<?php echo $escaper->escapeHtml($lang['Bytes']); ?><br />
                <?php
                        // If the max upload size for SimpleRisk is bigger than the PHP max upload size
                        if($simplerisk_max_upload_size > php_max_allowed_values())
                        {
                                echo "<font style=\"color: red;\">" . $escaper->escapeHtml($lang['WarnPHPUploadSize']) . '<br />';
                        }

                        // If the max upload size for SimpleRisk is bigger than the MySQL max upload size
                        if ($simplerisk_max_upload_size > mysql_max_allowed_values())
                        {
                                echo "<font style=\"color: red;\">" . $escaper->escapeHtml($lang['WarnMySQLUploadSize']) . '<br />';
                        }
                ?>
                <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_max_upload_size" /></td>
                            </tr>
                          </tbody>
                        </table>
                        </form>
                      </td>
                    </tr>
                    </tbody>
                    </table>
              </div>

              <!-- Mail Setting Tab -->
              <div id="mail" style="display: none;">
                <form name="mail_settings" method="post" action="">
		<?php
		        // Get the mail settings
		        $mail = get_mail_settings();
		        $transport = $mail['phpmailer_transport'];
		        $from_email = $mail['phpmailer_from_email'];
        		$from_name = $mail['phpmailer_from_name'];
        		$replyto_email = $mail['phpmailer_replyto_email'];
        		$replyto_name = $mail['phpmailer_replyto_name'];
        		$host = $mail['phpmailer_host'];
        		$smtpautotls = $mail['phpmailer_smtpautotls'];
        		$smtpauth = $mail['phpmailer_smtpauth'];
        		$username = $mail['phpmailer_username'];
        		$password = $mail['phpmailer_password'];
        		$encryption = $mail['phpmailer_smtpsecure'];
        		$port = $mail['phpmailer_port'];
		?>
                  <table border="1" width="600" cellpadding="10px">
                  <tbody>
                  <tr>
                    <td>
                      <table name="mail" id="mail" border="0">
                        <tr>
                          <td><?php echo $escaper->escapeHTML($lang['TransportAgent']); ?>:&nbsp;&nbsp;</td>
                          <td>
                            <select name="transport" id="transport" onchange="javascript: dropdown_transport()">
                              <option value="sendmail"<?php echo ($transport=="sendmail") ? " selected" : ""; ?>>sendmail</option>
                              <option value="smtp"<?php echo ($transport=="smtp") ? " selected" : ""; ?>>smtp</option>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td><?php echo $escaper->escapeHTML($lang['FromName']); ?>:&nbsp;&nbsp;</td>
                          <td><input type="text" name="from_name" value="<?php echo $escaper->escapeHTML($from_name); ?>" /></td>
                        </tr>
                        <tr>
                          <td><?php echo $escaper->escapeHTML($lang['FromEmail']); ?>:&nbsp;&nbsp;</td>
                          <td><input type="email" name="from_email" value="<?php echo $escaper->escapeHTML($from_email); ?>" /></td>
                        </tr>
                        <tr>
                          <td><?php echo $escaper->escapeHTML($lang['ReplyToName']); ?>:&nbsp;&nbsp;</td>
                          <td><input type="text" name="replyto_name" value="<?php echo $escaper->escapeHTML($replyto_name); ?>" /></td>
                        </tr>
                        <tr>
                          <td><?php echo $escaper->escapeHTML($lang['ReplyToEmail']); ?>:&nbsp;&nbsp;</td>
                          <td><input type="email" name="replyto_email" value="<?php echo $escaper->escapeHTML($replyto_email); ?>" /></td>
                        </tr>
                        <tr class="smtp"<?php echo ($transport=="sendmail") ? " style=\"display: none;\"" : "" ?>>
                          <td><?php echo $escaper->escapeHTML($lang['Host']); ?>:&nbsp;&nbsp;</td>
                          <td><input type="text" name="host" value="<?php echo $escaper->escapeHTML($host); ?>" /></td>
                        </tr>
                        <tr class="smtp"<?php echo ($transport=="sendmail") ? " style=\"display: none;\"" : "" ?>>
                          <td><?php echo $escaper->escapeHTML($lang['Port']); ?>:&nbsp;&nbsp;</td>
                          <td><input type="number" name="port" value="<?php echo $escaper->escapeHTML($port); ?>" /></td>
                        </tr>
                        <tr class="smtp"<?php echo ($transport=="sendmail") ? " style=\"display: none;\"" : "" ?>>
                          <td colspan="2"><input type="checkbox" name="smtpautotls" id="smtpautotls" <?php echo ($smtpautotls == "true") ? "checked=\"yes\" " : ""?>/>&nbsp;&nbsp;<?php echo $escaper->escapeHtml($lang['EnableTLSEncryptionAutomaticallyIfAServerSupportsIt']); ?></td>
                        </tr>
                        <tr class="smtp"<?php echo ($transport=="sendmail") ? " style=\"display: none;\"" : "" ?>>
                          <td colspan="2"><input type="checkbox" name="smtpauth" id="smtpauth" onchange="javascript: checkbox_smtpauth()" <?php echo ($smtpauth == "true") ? "checked=\"yes\" " : ""?>/>&nbsp;&nbsp;<?php echo $escaper->escapeHTML($lang['SMTPAuthentication']); ?></td>
                        </tr>
                        <tr class="smtpauth"<?php echo ($transport=="sendmail" || $smtpauth=="false") ? " style=\"display: none;\"" : "" ?>>
                          <td><?php echo $escaper->escapeHTML($lang['Username']); ?>:&nbsp;&nbsp;</td>
                          <td><input type="text" name="username" value="<?php echo $escaper->escapeHTML($username); ?>" /></td>
                        </tr>
                        <tr class="smtpauth"<?php echo ($transport=="sendmail" || $smtpauth=="false") ? " style=\"display: none;\"" : "" ?>>
                          <td><?php echo $escaper->escapeHTML($lang['Password']); ?>:&nbsp;&nbsp;</td>
                          <td><input type="password" name="password" value="" placeholder="Change Current Value" /></td>
                        </tr>
                        <tr class="smtpauth"<?php echo ($transport=="sendmail" || $smtpauth=="false") ? " style=\"display: none;\"" : "" ?>>
                          <td><?php echo $escaper->escapeHTML($lang['Encryption']); ?>:&nbsp;&nbsp;</td>
                          <td>
                            <select name="encryption" id="encryption">
                              <option value="none"<?php echo ($encryption=="none") ? " selected" : ""; ?>>None</option>
                              <option value="tls"<?php echo ($encryption=="tls") ? " selected" : ""; ?>>TLS</option>
                              <option value="ssl"<?php echo ($encryption=="ssl") ? " selected" : ""; ?>>SSL</option>
                            </select>
                          </td>
                        </tr>
                      </table>
                    <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Submit']); ?>" name="submit_mail" />
                  </td>
                </tr>
                </tbody>
                </table>

                <br />

                <table border="1" width="600" cellpadding="10px">
                <tbody>
                  <tr>
                    <td>
                      <table name="mail" id="mail" border="0">
                        <tr>
                          <td>
                            <u><strong><?php echo $escaper->escapeHtml($lang['TestMailSettings']); ?></strong></u></td>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <input type="text" name="email" size="50" placeholder="<?php echo $escaper->escapeHtml($lang['EmailAddress']); ?>"/>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <button type="submit" name="test_mail_configuration" class="btn btn-primary"><?php echo $escaper->escapeHtml($lang['Send']); ?></button>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </tbody>
                </table>

                </form>
              </div>

              <!-- Security Setting Tab -->
              <div id="security" style="display: none;">
                <form name="security_settings" method="post" action="">
                    <table border="1" width="600" cellpadding="10px">
                    <tbody>
                    <tr>
                      <td>
                        <table border="0" width="100%">
                          <tbody>
                        <tr>
                          <td colspan="2"><u><strong><?php echo $escaper->escapeHtml($lang['UserSessions']); ?></strong></u></td>
                        </tr>
                        <tr>
                          <td width="300px"><?php echo $escaper->escapeHtml($lang['SessionActivityTimeout']) . " (" . $escaper->escapeHtml($lang["seconds"]) . ")"; ?>:</td>
                          <td><input name="session_activity_timeout" id="session_activity_timeout" type="number" min="0" size="20px" value="<?php echo $escaper->escapeHtml(get_setting("session_activity_timeout")); ?>" /></td>
                        </tr>
                        <tr>
                          <td width="300px"><?php echo $escaper->escapeHtml($lang['SessionRenegotiationPeriod']) . " (" . $escaper->escapeHtml($lang["seconds"]) . ")"; ?>:</td>
                          <td><input name="session_renegotiation_period" id="session_renegotiation_period" type="number" min="0" size="20px" value="<?php echo $escaper->escapeHtml(get_setting("session_renegotiation_period")); ?>" /></td>
                        </tr>
                      </tbody>
                    </table>
                      </td>
                    </tr>
                    </tbody>
                    </table>
                    <br>
                    <table border="1" width="600" cellpadding="10px">
                    <tbody>
                    <tr>
                      <td>
                        <table border="0" width="100%">
                          <tbody>
                            <tr>
                              <td colspan="2"><u><strong><?php echo $escaper->escapeHtml($lang['Security']); ?></strong></u></td>
                            </tr>
                            <tr>
                              <td colspan="2"><input <?php if(get_setting('content_security_policy') == 1){ echo "checked"; } ?> name="content_security_policy" class="hidden-checkbox" size="2" value="90" id="content_security_policy" type="checkbox">  <label for="content_security_policy"  >&nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['EnableCSP']); ?></label></td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    </tbody>
                    </table>
                    <br />
                    <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_security_settings" />
                </form>
              </div>

              <!-- Debug Setting Tab -->
              <div id="debug" style="display: none;">
                <form name="debug_settings" method="post" action="">
                    <table border="1" width="600" cellpadding="10px">
                    <tbody>
                    <tr>
                      <td>
                        <table border="0" width="100%">
                          <tbody>
                            <tr>
                              <td colspan="2"><u><strong><?php echo $escaper->escapeHtml($lang['Debugging']); ?></strong></u></td>
                            </tr>
                            <tr>
                              <td colspan="2"><input <?php if(get_setting('debug_logging') == 1){ echo "checked"; } ?> name="debug_logging" class="hidden-checkbox" size="2" value="90" id="debug_logging" type="checkbox">  <label for="debug_logging"  >&nbsp;&nbsp; <?php echo $escaper->escapeHtml($lang['EnableDebugLogging']); ?></label></td>
                            </tr>
                            <tr>
                              <td width="300px"><?php echo $escaper->escapeHtml($lang['DebugLogFile']); ?>:</td>
                              <td><input name="debug_log_file" id="debug_log_file" type="text" size="20px" value="<?php echo $escaper->escapeHtml(get_setting("debug_log_file")); ?>" /></td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                    </tbody>
                    </table>
                    <br />
                    <input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_debug_settings" />
                </form>
              </div>

              <!-- Extras Setting Tab -->
              <!--<div id="extras" style="display: none;">
                Extra Text
              </div>-->

          </div>
        </div>
      </div>
    </div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>
</body>
</html>