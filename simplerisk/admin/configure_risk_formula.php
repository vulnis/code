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

    // Check if the risk level update was submitted
    if (isset($_POST['update_risk_levels']))
    {
        $level = $_POST['level'];
		$veryhigh = $level['Very High'];
        $high = $level['High'];
        $medium = $level['Medium'];
        $low = $level['Low'];
        $risk_model = (int)$_POST['risk_models'];

        // Check if all values are integers
        if (is_numeric($veryhigh['value']) && is_numeric($high['value']) && is_numeric($medium['value']) && is_numeric($low['value']) && is_int($risk_model))
        {
            // Check if low < medium < high < very high
            if (($low['value'] < $medium['value']) && ($medium['value'] < $high) && ($high['value'] < $veryhigh['value']))
            {
                // Update the risk level
                update_risk_levels($veryhigh, $high, $medium, $low);

				// Risk model should be between 1 and 5
				if ((1 <= $risk_model) && ($risk_model <= 5))
				{
					// Update the risk model
					update_risk_model($risk_model);

					// Display an alert
					set_alert(true, "good", "The configuration was updated successfully.");
				}
                // Otherwise, there was a problem
                else
                {
				    // Display an alert
				    set_alert(true, "bad", "The risk formula submitted was an invalid value.");
                }
            }
			// Otherwise, there was a problem
			else
			{
				// Display an alert
				set_alert(true, "bad", "Your LOW risk needs to be less than your MEDIUM risk which needs to be less than your HIGH risk which needs to be less than your VERY HIGH risk.");
			}
        }
		// Otherwise, there was a problem
		else
		{
			// Display an alert
			set_alert(true, "bad", "One of the submitted risk values is not a numeric value.");
		}
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
            set_alert(true, "good", $escaper->escapeHtml($lang['SuccessUpdatingImpactName']));

            refresh();
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
            set_alert(true, "good", $escaper->escapeHtml($lang['SuccessUpdatingLikelihoodName']));
            
            refresh();
        }
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
            <?php view_configure_menu("ConfigureRiskFormula"); ?>
        </div>
        <div class="col-9">
            <form name="impact" method="post" action="">
                <h4><?php echo $escaper->escapeHtml($lang['Impact']); ?></h4>
                <p>
                    <?php echo $escaper->escapeHtml($lang['Change']); ?> <?php create_dropdown("impact") ?> <?php echo $escaper->escapeHtml($lang['to']); ?> <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_impact" />
                </p>
            </form>
            <form name="likelihood" method="post" action="">
                <h4><?php echo $escaper->escapeHtml($lang['Likelihood']); ?></h4>
                <p>
                    <?php echo $escaper->escapeHtml($lang['Change']); ?> <?php create_dropdown("likelihood") ?> <?php echo $escaper->escapeHtml($lang['to']); ?> <input name="new_name" type="text" size="20" />&nbsp;&nbsp;<input type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_likelihood" />
                </p>
            </form>
            <form name="risk_levels" method="post" action="">
                <h4><?php echo $escaper->escapeHtml($lang['MyClassicRiskFormulaIs']); ?></h4>
                <p><?php echo $escaper->escapeHtml($lang['RISK']); ?> = <?php create_dropdown("risk_models", get_setting("risk_model"), null, false) ?></p>
                <?php $risk_levels = get_risk_levels(); ?>
                <div>
                    <?php echo $escaper->escapeHtml($lang['IConsiderVeryHighRiskToBeAnythingGreaterThan']); ?>:
                    <input type="text" name="level[Very High][value]" size="2" value="<?php echo $escaper->escapeHtml($risk_levels[3]['value']); ?>" />
                    <input class="level-colorpicker level-color" type="hidden" name="level[Very High][color]" value="<?php echo $escaper->escapeHtml($risk_levels[3]['color']); ?>">
                    <div class="colorSelector">
                        <div style="background-color: <?php echo $escaper->escapeHtml($risk_levels[3]['color']); ?>;"></div>
                    </div>
                    <input type="text" required name="level[Very High][display_name]" size="2" value="<?php echo $escaper->escapeHtml($risk_levels[3]['display_name']); ?>" />
                </div>
                <div>
                    <?php echo $escaper->escapeHtml($lang['IConsiderHighRiskToBeLessThanAboveButGreaterThan']); ?>:
                    <input type="text" name="level[High][value]" size="2" value="<?php echo $escaper->escapeHtml($risk_levels[2]['value']); ?>" />
                    <input class="level-colorpicker level-color" type="hidden" name="level[High][color]" value="<?php echo $escaper->escapeHtml($risk_levels[2]['color']); ?>">
                    <div class="colorSelector">
                        <div style="background-color: <?php echo $escaper->escapeHtml($risk_levels[2]['color']); ?>;"></div>
                    </div>
                    <input type="text" required name="level[High][display_name]" size="2" value="<?php echo $escaper->escapeHtml($risk_levels[2]['display_name']); ?>" />
                </div>
                <div>
                    <?php echo $escaper->escapeHtml($lang['IConsiderMediumRiskToBeLessThanAboveButGreaterThan']); ?>:
                    <input type="text" name="level[Medium][value]" size="2" value="<?php echo $escaper->escapeHtml($risk_levels[1]['value']); ?>" />
                    <input class="level-colorpicker level-color" type="hidden" name="level[Medium][color]" value="<?php echo $escaper->escapeHtml($risk_levels[1]['color']); ?>">
                    <div class="colorSelector">
                        <div style="background-color: <?php echo $escaper->escapeHtml($risk_levels[1]['color']); ?>;"></div>
                    </div>
                    <input type="text" required name="level[Medium][display_name]" size="2" value="<?php echo $escaper->escapeHtml($risk_levels[1]['display_name']); ?>" />
                </div>
                <div>
                    <?php echo $escaper->escapeHtml($lang['IConsiderlowRiskToBeLessThanAboveButGreaterThan']); ?>:
                    <input type="text" name="level[Low][value]" size="2" value="<?php echo $escaper->escapeHtml($risk_levels[0]['value']); ?>" />
                    <input class="level-colorpicker level-color" type="hidden" name="level[Low][color]" value="<?php echo $escaper->escapeHtml($risk_levels[0]['color']); ?>">
                    <div class="colorSelector">
                        <div style="background-color: <?php echo $escaper->escapeHtml($risk_levels[0]['color']); ?>;"></div>
                    </div>
                    <input type="text" required name="level[Low][display_name]" size="2" value="<?php echo $escaper->escapeHtml($risk_levels[0]['display_name']); ?>" />
                </div>
                <input class="btn btn-primary" type="submit" value="<?php echo $escaper->escapeHtml($lang['Update']); ?>" name="update_risk_levels" />
            </form>

            <?php create_risk_formula_table(); ?>

            <?php echo "<p><font size=\"1\">* " . $escaper->escapeHtml($lang['AllRiskScoresAreAdjusted']) . "</font></p>"; ?>
        </div>
    </div>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>