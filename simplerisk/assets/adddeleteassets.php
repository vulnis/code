<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
* License, v. 2.0. If a copy of the MPL was not distributed with this
* file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Include required functions file
require_once(realpath(__DIR__ . '/../includes/assets.php'));
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

// Check if the user has access to manage assets
if (!isset($_SESSION["asset"]) || $_SESSION["asset"] != 1)
{
  header("Location: ../index.php");
  exit(0);
}
else $manage_assets = true;

// Check if an asset was added
if ((isset($_POST['add_asset'])) && $manage_assets)
{
  $name = $_POST['asset_name'];
  $ip = $_POST['ip'];
  $value = $_POST['value'];
  $location = $_POST['location'];
  $team = $_POST['team'];
  $details = $_POST['details'];

  // Add the asset
  $success = add_asset($ip, $name, $value, $location, $team, $details);

  // If the asset add was successful
  if ($success)
  {
    // Display an alert
    set_alert(true, "good", $lang['AssetWasAddedSuccessfully']);
  }
  else
  {
    // Display an alert
    set_alert(true, "bad", $lang['ThereWasAProblemAddingTheAsset']);
  }
}

// Check if assets were deleted
if ((isset($_POST['delete_assets'])) && $manage_assets)
{
  $assets = $_POST['assets'];

  // Delete the assets
  $success = delete_assets($assets);

  // If the asset delete was successful
  if ($success)
  {
    // Display an alert
    set_alert(true, "good", $lang['AssetWasDeletedSuccessfully']);
  }
  else
  {
    // Display an alert
    set_alert(true, "bad", $lang['ThereWasAProblemDeletingTheAsset']);
  }
}

?>
<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); ?>
<body>
  <?php
  view_top_menu("AssetManagement");

  // Get any alert messages
  get_alert();
  ?>
  <div id="load" style="display:none;">Scanning IPs... Please wait.</div>
  <div class="container">
    <div class="row">
      <div class="col-3">
        <?php view_asset_management_menu("AddDeleteAssets"); ?>
      </div>
      <div class="col-9">
        <div class="row">
          <div class="col-12">
              <h3><?php echo $escaper->escapeHTML($lang['AddANewAsset']); ?></h3>
              <form name="add" method="post" action="" id="add-asset-container">
                <div class="form-group">
                  <label for="asset_name">
                    <?php echo $escaper->escapeHTML($lang['AssetName']); ?>
                  </label>
                  <input class="form-control" type="text" id="asset_name" name="asset_name" />
                </div>
                <div class="form-group">
                  <label for="ip">
                  <?php echo $escaper->escapeHTML($lang['IPAddress']); ?>
                  </label>
                  <input class="form-control" type="text" name="ip" />
                </div>
                <div class="form-group">
                  <label for="value">
                    <?php echo $escaper->escapeHTML($lang['AssetValuation']); ?>
                  </label>
                  <?php
                        // Get the default asset valuation
                        $default = get_default_asset_valuation();
                        // Create the asset valuation dropdown
                        create_asset_valuation_dropdown("value", $default);
                  ?>
                </div>
                <div class="form-group">
                  <label for="location">
                  <?php echo $escaper->escapeHTML($lang['SiteLocation']); ?>
                  </label>
                  <?php create_dropdown("location"); ?></td>
                </div>
                <div class="form-group">
                  <label for="team">
                  <?php echo $escaper->escapeHTML($lang['Team']); ?>
                  </label>
                  <?php create_dropdown("team"); ?></td>
                </div>
                <div class="form-group">
                  <label for="team">
                  <?php echo $escaper->escapeHTML($lang['AssetDetails']); ?>
                  </label>
                  <textarea name="details"  style="width: 100%;"></textarea>
                </div>
                <button type="submit" name="add_asset" class="btn btn-primary"><?php echo $escaper->escapeHtml($lang['Add']); ?></button>
                </form>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <h3><?php echo $escaper->escapeHTML($lang['DeleteAnExistingAsset']); ?></h3>
                <form name="delete" method="post" action="">
                  <?php display_asset_table(); ?>
                  <button type="submit" name="delete_assets" class="btn btn-primary"><?php echo $escaper->escapeHtml($lang['Delete']); ?></button>
                </form>

              </div>
            </div>
        </div>
      </div>
    </div>

<?php display_simple_autocomplete_script(get_unentered_assets()); ?>
<?php display_set_default_date_format_script(); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>
</body>
</html>