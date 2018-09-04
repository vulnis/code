<?php
    /* This Source Code Form is subject to the terms of the Mozilla Public
      * License, v. 2.0. If a copy of the MPL was not distributed with this
      * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

    // Include required functions file
    require_once(realpath(__DIR__ . '/../includes/functions.php'));
    require_once(realpath(__DIR__ . '/../includes/authenticate.php'));
    require_once(realpath(__DIR__ . '/../includes/display.php'));
    require_once(realpath(__DIR__ . '/../includes/messages.php'));
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

    // If the language was changed
    if (isset($_POST['change_language']))
    {
        $language = (int)$_POST['languages'];

        // If its not the default selection
        if ($language != 0)
        {
            // Update the language for the current user
            update_language($_SESSION['uid'], get_name_by_value("languages", $language));

            // Use the new language file
            require_once(language_file());

            // Display an alert
            set_alert(true, "good", $lang['LanguageUpdated']);
        }
        else
        {
            // Display an alert
            set_alert(true, "bad", $lang['SelectValidLanguage']);
        }
    }

    // Include the language file
    require_once(language_file());

    // Get the users information
    $user_info = get_user_by_id($_SESSION['uid']);
    $username = $user_info['username'];
    $name = $user_info['name'];
    $email = $user_info['email'];
    $last_login = date(get_default_datetime_format(), strtotime($user_info['last_login']));
    $teams = $user_info['teams'];
    $language = $user_info['lang'];
    $asset = $user_info['asset'];
    $governance = $user_info['governance'];
    $riskmanagement = $user_info['riskmanagement'];
    $compliance = $user_info['compliance'];
    $assessments = $user_info['assessments'];
    $admin = $user_info['admin'];
    $review_veryhigh = $user_info['review_veryhigh'];
    $accept_mitigation = $user_info['accept_mitigation'];
    $review_high = $user_info['review_high'];
    $review_medium = $user_info['review_medium'];
    $review_low = $user_info['review_low'];
    $review_insignificant = $user_info['review_insignificant'];
    $submit_risks = $user_info['submit_risks'];
    $modify_risks = $user_info['modify_risks'];
    $plan_mitigations = $user_info['plan_mitigations'];
    $close_risks = $user_info['close_risks'];
    
    $add_new_frameworks = $user_info['add_new_frameworks'];
    $modify_frameworks = $user_info['modify_frameworks'];
    $delete_frameworks = $user_info['delete_frameworks'];
    $add_new_controls = $user_info['add_new_controls'];
    $modify_controls = $user_info['modify_controls'];
    $delete_controls = $user_info['delete_controls'];
    $add_documentation = $user_info['add_documentation'];
    $modify_documentation = $user_info['modify_documentation'];
    $delete_documentation = $user_info['delete_documentation'];
    $comment_risk_management = $user_info['comment_risk_management'];
    $comment_compliance = $user_info['comment_compliance'];
    
    $role_id = $user_info['role_id'];

    // Check if a new password was submitted
    if (isset($_POST['change_password']))
    {
        $user = $_SESSION["user"];
        $current_pass = $_POST['current_pass'];
        $new_pass = $_POST['new_pass'];
        $confirm_pass = $_POST['confirm_pass'];

        // If the user and current password are valid
        if (is_valid_user($user, $current_pass))
        {
            // Check the password
            $error_code = valid_password($new_pass, $confirm_pass, $_SESSION['uid']);

            // If the password is valid
            if ($error_code == 1)
            {
                // Generate the salt
                $salt = generateSalt($user);

                // Generate the password hash
                $hash = generateHash($salt, $new_pass);
                
                // If it is possible to reuse password
                if(check_add_password_reuse_history($_SESSION["uid"], $hash)){
                    // Get user old data
                    $old_data = get_salt_and_password_by_user_id($_SESSION['uid']);

                    // Add the old data to the pass_history table
                    add_last_password_history($_SESSION["uid"], $old_data["salt"], $old_data["password"]);


                    // Update the password
                    update_password($user, $hash);

                    // If the encryption extra is enabled
                    if (encryption_extra())
                    {
                        // Load the extra
                        require_once(realpath(__DIR__ . '/../extras/encryption/index.php'));

                        // Set the new encrypted password
                        set_enc_pass($user, $new_pass, $_SESSION['encrypted_pass']);
                    }

                    // Display an alert
                    set_alert(true, "good", $lang['PasswordUpdated']);
                }else{
                    set_alert(true, "bad", $lang['PasswordNoLongerUse']);
                }
            }
            else
            {
                // Display an alert
                //set_alert(true, "bad", password_error_message($error_code));
            }
        }
        else
        {
            // Display an alert
            set_alert(true, "bad", $lang['PasswordIncorrect']);
        }
    }
    
    // Check if a reset_custom_display_setting button is clicked
    if(isset($_POST['reset_custom_display_settings'])){
        reset_custom_display_settings();
        // Display an alert
        set_alert(true, "good", $lang['CustomResetSuccessMessage']);
        
    }
?>

<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); ?>
<script type="text/javascript">
    $(function(){
            $(document).ready(function(){
                $("#team").multiselect({
                    allSelectedText: '<?php echo $escaper->escapeHtml($lang['AllTeams']); ?>',
                    includeSelectAllOption: true
                });
                // role event
                $("#role").change(function(){
//                    setUserResponsibilitesByRole();
                })
            });
            
            function setUserResponsibilitesByRole(){
                // If role is unselected, uncheck all responsibilities
                if(!$("#role").val())
                {
                    $(".checklist input[type=checkbox]").prop("checked", false);
                    return;
                }
                else if($("#role").val() == 1)
                {
                    // Set all user responsibilites
                    $(".checklist input[type=checkbox]").prop("checked", true);
                    
                    // Set all teams
                    $("#team").multiselect("selectAll", false);
                    $("#team").multiselect("refresh");
                }
                else
                {
                    $.ajax({
                        type: "GET",
                        url: BASE_URL + "/api/role_responsibilities/get_responsibilities",
                        data: {
                            role_id: $("#role").val()
                        },
                        success: function(data){
                            // Uncheck all checkboxes
                            $(".checklist input[type=checkbox]").prop("checked", false);
                            
                            // Check all for responsibilites
                            var responsibility_names = data.data;
                            for(var key in responsibility_names){
                                $(".checklist input[name="+responsibility_names[key]+"]").prop("checked", true)
                            }
                        },
                        error: function(xhr,status,error){
                            if(xhr.responseJSON && xhr.responseJSON.status_message){
                                $('#show-alert').html(xhr.responseJSON.status_message);
                            }
                        }
                    })
                }
            }
        });
    </script>
<?php
    view_top_menu("Configure");

    // Get any alert messages
    get_alert();
?>
<div class="container-fluid">
    <div class="row p-5">
        <div class="col-lg-6 col-12 mx-auto">
            <nav>
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="true"><?php echo $escaper->escapeHtml($lang['ProfileDetails']); ?></a>
                    <a class="nav-item nav-link" id="nav-rights-tab" data-toggle="tab" href="#nav-rights" role="tab" aria-controls="nav-rights" aria-selected="false"><?php echo $escaper->escapeHtml($lang['UserResponsibilities']); ?></a>
                    <a class="nav-item nav-link" id="nav-password-tab" data-toggle="tab" href="#nav-password" role="tab" aria-controls="nav-password" aria-selected="false"><?php echo $escaper->escapeHtml($lang['ChangePassword']); ?></a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <form name="change_language" method="post" action="">
                        <div class="form-group">
                            <label for="name">
                                <?php echo $escaper->escapeHtml($lang['FullName']); ?>
                            </label>
                            <input class="form-control" name="name" type="text" value="<?php echo $escaper->escapeHtml($name); ?>" readonly />
                        </div>
                        <div class="form-group">
                            <label for="email">
                                <?php echo $escaper->escapeHtml($lang['EmailAddress']); ?>
                            </label>
                            <input class="form-control" name="email" type="text" value="<?php echo $escaper->escapeHtml($email); ?>" readonly />
                        </div>
                        <div class="form-group">
                            <label for="username">
                                <?php echo $escaper->escapeHtml($lang['Username']); ?>
                            </label>
                            <input class="form-control" name="username" type="text" value="<?php echo $escaper->escapeHtml($username); ?>" readonly />
                        </div>
                        <div class="form-group">
                            <label for="last_login">
                                <?php echo $escaper->escapeHtml($lang['LastLogin']); ?>
                            </label>
                            <input class="form-control" name="last_login" type="text" value="<?php echo $escaper->escapeHtml($last_login); ?>"  readonly />
                        </div>
                        <div class="form-group">
                            <label for="language">
                                <?php echo $escaper->escapeHtml($lang['Language']); ?>
                            </label>
                            <?php create_dropdown("languages", get_value_by_name("languages", $language)); ?>
                        </div>
                        
                        <?php
                            // If the API Extra is enabled
                            if (api_extra())
                            {
                                // Require the API Extra
                                require_once(realpath(__DIR__ . '/../extras/api/index.php'));

                                // Display the API Profile
                                display_api_profile();
                            }
                        ?>
                        <div class="form-group">
                            <label for="teams">
                                <?php echo $escaper->escapeHtml($lang['Teams']); ?>
                            </label>
                            <?php create_multiple_dropdown("team", $teams); ?>
                        </div>
                        <div class="form-group">
                            <label for="rol">
                                <?php echo $escaper->escapeHtml($lang['Role']); ?>
                            </label>
                            <?php create_dropdown("role", $role_id); ?>
                        </div>
                        <input class="btn btn-secondary" name="reset_custom_display_settings" value="<?php echo $lang['ResetCustomDisplaySettings']; ?>" type="submit">
                        <input class="btn btn-primary" type="submit" name="change_language" value="<?php echo $escaper->escapeHtml($lang['Submit']); ?>" />
                    </form>
                </div>
                <div class="tab-pane fade" id="nav-rights" role="tabpanel" aria-labelledby="nav-rights-tab">
                    <table class="table checklist">
                        <tr><td colspan="2"><?php echo $escaper->escapeHtml($lang['Governance']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="governance" type="checkbox"<?php if ($governance) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AllowAccessToGovernanceMenu']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="add_new_frameworks" type="checkbox"<?php if ($add_new_frameworks) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToAddNewFrameworks']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="modify_frameworks" type="checkbox"<?php if ($modify_frameworks) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToModifyExistingFrameworks']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="delete_frameworks" type="checkbox"<?php if ($delete_frameworks) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToDeleteExistingFrameworks']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="add_new_controls" type="checkbox"<?php if ($add_new_controls) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToAddNewControls']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="modify_controls" type="checkbox"<?php if ($modify_controls) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToModifyExistingControls']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="delete_controls" type="checkbox"<?php if ($delete_controls) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToDeleteExistingControls']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="add_documentation" type="checkbox"<?php if ($add_documentation) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToAddDocumentation']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="modify_documentation" type="checkbox"<?php if ($modify_documentation) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToModifyDocumentation']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="delete_documentation" type="checkbox"<?php if ($delete_documentation) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToDeleteDocumentation']); ?></td></tr>
                        <tr><td colspan="2"><?php echo $escaper->escapeHtml($lang['RiskManagement']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="riskmanagement" type="checkbox"<?php if ($riskmanagement) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AllowAccessToRiskManagementMenu']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="submit_risks" type="checkbox"<?php if ($submit_risks) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToSubmitNewRisks']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="modify_risks" type="checkbox"<?php if ($modify_risks) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToModifyExistingRisks']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="close_risks" type="checkbox"<?php if ($close_risks) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToCloseRisks']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="plan_mitigations" type="checkbox"<?php if ($plan_mitigations) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToPlanMitigations']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="accept_mitigation" type="checkbox"<?php if ($accept_mitigation) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToAcceptMitigations']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="review_insignificant" type="checkbox"<?php if ($review_insignificant) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToReviewInsignificantRisks']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="review_low" type="checkbox"<?php if ($review_low) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToReviewLowRisks']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="review_medium" type="checkbox"<?php if ($review_medium) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToReviewMediumRisks']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="review_high" type="checkbox"<?php if ($review_high) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToReviewHighRisks']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="review_veryhigh" type="checkbox"<?php if ($review_veryhigh) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToReviewVeryHighRisks']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="comment_risk_management" type="checkbox"<?php if ($comment_risk_management) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToCommentRiskManagement']); ?></td></tr>
                        <tr><td colspan="2"><?php echo $escaper->escapeHtml($lang['Compliance']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="compliance" type="checkbox"<?php if ($compliance) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AllowAccessToComplianceMenu']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="comment_compliance" type="checkbox"<?php if ($comment_compliance) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AbleToCommentCompliance']); ?></td></tr>
                        <tr><td colspan="2"><?php echo $escaper->escapeHtml($lang['AssetManagement']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="asset" type="checkbox"<?php if ($asset) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AllowAccessToAssetManagementMenu']); ?></td></tr>
                        <tr><td colspan="2"><?php echo $escaper->escapeHtml($lang['Assessments']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="assessments" type="checkbox"<?php if ($assessments) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AllowAccessToAssessmentsMenu']); ?></td></tr>
                        <tr><td colspan="2"><?php echo $escaper->escapeHtml($lang['Configure']); ?></td></tr>
                        <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="admin" type="checkbox"<?php if ($admin) echo " checked" ?> />&nbsp;<?php echo $escaper->escapeHtml($lang['AllowAccessToConfigureMenu']); ?></td></tr>
                    </table>
                </div>
                <div class="tab-pane fade" id="nav-password" role="tabpanel" aria-labelledby="nav-password-tab">
                    <?php
                        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] != "ldap")
                        { ?>
                    <form name="change_password" method="post" action="">
                        <?php $resetRequestMessages = getPasswordReqeustMessages(); ?>
                        <div class="form-group">
                            <label for="current_pass"><?php echo $escaper->escapeHtml($lang['CurrentPassword']) ?></label>
                            <input name="current_pass" id="current_pass" class="form-control" type="password" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label for="new_pass"><?php echo $escaper->escapeHtml($lang['NewPassword']) ?></label>
                            <input name="new_pass" id="new_pass" class="form-control" type="password" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label for="confirm_pass"><?php echo $escaper->escapeHtml($lang['ConfirmPassword']) ?></label>
                            <input name="confirm_pass" id="confirm_pass" class="form-control" type="password" autocomplete="off" />
                            <?php 
                            if(count($resetRequestMessages))
                            {
                                foreach($resetRequestMessages as $resetRequestMessage)
                                { 
                                    echo "<small class=\"form-text text-muted\">{$resetRequestMessage}</small>\n";
                                } 
                            } ?>
                        </div>
                        <button class="btn btn-secondary" type="reset"><?php echo $escaper->escapeHtml($lang['Reset']) ?></button>
                        <button type="submit" name="change_password" class="btn btn-primary"><?php echo $escaper->escapeHtml($lang['Submit']) ?></button>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    display_set_default_date_format_script();
    include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); 
?>