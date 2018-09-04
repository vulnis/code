<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
* License, v. 2.0. If a copy of the MPL was not distributed with this
* file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Include required functions file
require_once(realpath(__DIR__ . '/includes/functions.php'));
require_once(realpath(__DIR__ . '/includes/authenticate.php'));
require_once(realpath(__DIR__ . '/includes/display.php'));
require_once(realpath(__DIR__ . '/includes/alerts.php'));

// Include Zend Escaper for HTML Output Encoding
require_once(realpath(__DIR__ . '/includes/Component_ZendEscaper/Escaper.php'));
$escaper = new Zend\Escaper\Escaper('utf-8');

// Add various security headers
add_security_headers();

// Session handler is database
if (USE_DATABASE_FOR_SESSIONS == "true")
{
	session_set_save_handler('sess_open', 'sess_close', 'sess_read', 'sess_write', 'sess_destroy', 'sess_gc');
}

// Start session
session_set_cookie_params(0, '/', '', isset($_SERVER["HTTPS"]), true);

if (!isset($_SESSION))
{
	sess_gc(1440);
	session_name('SimpleRisk');
	session_start();
}

// Include the language file
require_once(language_file());

// If the login form was posted
if (isset($_POST['submit']))
{
	$user = $_POST['user'];
	$pass = $_POST['pass'];

	// Check for expired lockouts
	check_expired_lockouts();

	// If the user is valid
	if (is_valid_user($user, $pass))
	{
        	$uid = get_id_by_user($user);
        	$array = get_user_by_id($uid);

            	if($array['change_password'])
		{
                	$_SESSION['first_login_uid'] = $uid;

            		if (encryption_extra())
            		{
                		// Load the extra
                		require_once(realpath(__DIR__ . '/extras/encryption/index.php'));

                		// Get the current password encrypted with the temp key
                		check_user_enc($user, $pass);
            		}

            		header("location: reset_password.php");
			exit;
        	}

		// Create the SimpleRisk instance ID if it doesn't already exist
		create_simplerisk_instance_id();

		// Ping the server
		ping_server();

        	// Set login status
        	login($user, $pass);

  	}
  	// If the user is not a valid user
  	else
  	{
		$_SESSION["access"] = "denied";

		// Display an alert
		set_alert(true, "bad", "Invalid username or password.");

		// If the password attempt lockout is enabled
		if(get_setting("pass_policy_attempt_lockout") != 0)
		{
			// Add the login attempt and block if necessary
			add_login_attempt_and_block($user);
		}
  	}
}

if (isset($_SESSION["access"]) && ($_SESSION["access"] == "granted"))
{
	// Select where to redirect the user next
	select_redirect();
}

// If the user has already authorized and we are authorizing with duo
if (isset($_SESSION["access"]) && ($_SESSION["access"] == "duo"))
{
	// If a response has been posted
  	if (isset($_POST['sig_response']))
  	{
    		// Include the custom authentication extra
    		require_once(realpath(__DIR__ . '/extras/authentication/index.php'));

    		// Get the authentication settings
    		$configs = get_authentication_settings();

    		// For each configuration
    		foreach ($configs as $config)
    		{
      			// Set the name value pair as a variable
      			${$config['name']} = $config['value'];
    		}

    		// Get the response back from Duo
    		$resp = Duo\Web::verifyResponse($IKEY, $SKEY, get_duo_akey(), $_POST['sig_response']);

    		// If the response is not null
    		if ($resp != NULL)
    		{

      			// If the encryption extra is enabled
      			if (encryption_extra())
      			{
        			// Load the extra
        			require_once(realpath(__DIR__ . '/extras/encryption/index.php'));

        			// Check user enc
        			check_user_enc($user, $pass);
      			}

      			// Grant the user access
      			grant_access();

			// Select where to redirect the user next
			select_redirect();
    		}
  	}
}
?>
<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php 
    include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php');
    view_top_menu("Home");

  // If the user has authenticated and now we need to authenticate with duo
  if (isset($_SESSION["access"]) && $_SESSION["access"] == "duo")
  {
    echo "<div class=\"row\">\n";
    echo "<div class=\"col-9\">\n";
    // echo "<div class=\"well\">\n";

    // Include the custom authentication extra
    require_once(realpath(__DIR__ . '/extras/authentication/index.php'));

    // Perform a duo authentication request for the user
    duo_authentication($_SESSION["user"]);

    // echo "</div>\n";
    echo "</div>\n";
    echo "</div>\n";
  }
  // If the user has not authenticated
  else if (!isset($_SESSION["access"]) || $_SESSION["access"] != "granted")
  {
	get_alert();
	include_once($_SERVER['DOCUMENT_ROOT'].'/templates/login.php');
  }
  ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>
