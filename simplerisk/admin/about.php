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
?>
<?php 
    include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); 
    view_top_menu("Configure");
    get_alert();
?>
<div class="container-fluid">
    <div class="row p-5">
        <div class="col-3">
            <?php view_configure_menu("About"); ?>
        </div>
        <div class="col-9">
            <p>The use of this software is subject to the terms of the <a href="http://mozilla.org/MPL/2.0/" target="newwindow">Mozilla Public License, v. 2.0</a>.</p>
            <h4>Application Version</h4>
            <p>
            <ul>
                <li>The latest Application version is <?php echo $escaper->escapeHtml(latest_version("app")); ?></li>
                <li>You are running Application version <?php echo $escaper->escapeHtml(current_version("app")); ?></li>
            </ul>
            </p>
            <h4>Database Version</h4>
            <p>
            <ul>
                <li>The latest Database version is <?php echo $escaper->escapeHtml(latest_version("db")); ?></li>
                <li>You are running Database version <?php echo $escaper->escapeHtml(current_version("db")); ?></li>
            </ul>
            </p>
            <p>You can download the most recent code <a href="https://www.simplerisk.com/download" target="newwindow">here</a>.</p>
            <h4>Donate</h4>
            <p><a href="http://www.joshsokol.com" target="newwindow">Josh Sokol</a> wrote this Risk Management system after being fed up with the high-priced alternatives out there.  When your only options are spending tens of thousands of dollars or using a spreadsheet, good risk management is simply unattainable.</p>
            <!-- START PAYPAL FORM -->
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="josh@simplerisk.org">
                <input type="hidden" name="item_name" value="Donation for Risk Management Software">
                <input type="hidden" name="no_note" value="1">
                <input type="hidden" name="currency_code" value="USD">
                <input type="hidden" name="on0" value="Project Details">
                <div class="form-group">
                    <label for="amount">Enter amount</label>
                    <input type="text" name="amount" value="50.00" class="form-control">
                    <small class="text text-muted">Josh lives in Austin, TX and has four little ones starving for his time and attention.  If this tool is useful to you and you want to encourage him to keep his attention fixed on developing new features for you, perhaps consider donating via the PayPal form on the right.  It&#39;s also good karma.</small>
                </div>
                <div class="form-group">
                    <label for="os0">Payment notes</label>
                    <textarea name="os0" rows="3" cols="17" class="form-control"></textarea>
                </div>
                <button type="submit" name="PaypalPayment" class="btn btn-lg btn-success">
                    <i class="fa fa-paypal" aria-hidden="true"></i>
                    Donate with Paypal
                </button>
                </form>
                <!-- END PAYPAL FORM -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>
