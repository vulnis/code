<?php
// Include required functions file
require_once(realpath(__DIR__ . '/../../includes/functions.php'));
require_once(realpath(__DIR__ . '/../../includes/authenticate.php'));
require_once(realpath(__DIR__ . '/../../includes/display.php'));
require_once(realpath(__DIR__ . '/../../includes/alerts.php'));
require_once(realpath(__DIR__ . '/../../includes/permissions.php'));

// Include Zend Escaper for HTML Output Encoding
require_once(realpath(__DIR__ . '/../../includes/Component_ZendEscaper/Escaper.php'));
$escaper = new Zend\Escaper\Escaper('utf-8');

// Add various security headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// If we want to enable the Content Security Policy (CSP) - This may break Chrome
if (csp_enabled())
{
  // Add the Content-Security-Policy header
  header("Content-Security-Policy: default-src 'self' 'unsafe-inline';");
}

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
global $lang;

require_once(realpath(__DIR__ . '/../../includes/csrf-magic/csrf-magic.php'));

// Check for session timeout or renegotiation
session_check();

// Check if access is authorized
if (!isset($_SESSION["access"]) || $_SESSION["access"] != "granted")
{
  header("Location: ../../index.php");
  exit(0);
}

// Enforce that the user has access to risk management
enforce_permission_riskmanagement();

?>

<!-- Edit the risk details-->
    <div id="edit-details-btn" class="tabs--action">
        <button type="submit" name="edit_details" class="btn"><?php echo $escaper->escapeHtml($lang['EditDetails']); ?></button>
    </div>
    
<!-- Edit mitigation -->
    <div class="tabs--action">
        <button type="submit" name="edit_mitigation" class="btn"><?php echo $escaper->escapeHtml($lang['EditMitigation']); ?></button>
    </div>

<!-- View all reviews-->
    <div class="tabs--action">
        <a class="btn" href="reviews.php?id=<?php echo $escaper->escapeHtml($id); ?>" ><?php echo $escaper->escapeHtml($lang['ViewAllReviews']); ?></a>
    </div>
    
<!-- Cancel and update mitigations -->
    <div class="tabs--action">
        <a href='/management/view.php?id=$risk_id&type=1' id="cancel_disable" class="btn btn-secondary"><?php echo $escaper->escapeHtml($lang['Cancel']); ?> </a>
        <button type="submit" name="update_mitigation" class="btn btn-primary"><?php echo $escaper->escapeHtml($lang['SaveMitigation']) ?></button>
    </div>

<!-- Cancel and update risk -->
    <div class="tabs--action">
        <a href='/management/view.php?id=$id&type=0' id="cancel_disable" class="btn btn-secondary"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></a>
        <button type="submit" name="update_details" class="btn btn-primary"><?php echo $escaper->escapeHtml($lang['SaveDetails']); ?></button>
    </div>
        
        
        <input type="hidden" id="_token_value" value="<?php echo csrf_get_tokens(); ?>">
        <input type="hidden" id="_lang_reopen_risk" value="<?php echo $lang['ReopenRisk']; ?>">
        <input type="hidden" id="_lang_close_risk" value="<?php echo $lang['CloseRisk']; ?>">
