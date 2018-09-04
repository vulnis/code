<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
* License, v. 2.0. If a copy of the MPL was not distributed with this
* file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Include required functions file
require_once(realpath(__DIR__ . '/../includes/functions.php'));
require_once(realpath(__DIR__ . '/../includes/authenticate.php'));
require_once(realpath(__DIR__ . '/../includes/display.php'));
require_once(realpath(__DIR__ . '/../includes/alerts.php'));
require_once(realpath(__DIR__ . '/../includes/permissions.php'));
require_once(realpath(__DIR__ . '/../includes/governance.php'));

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

function csrf_startup() {
    csrf_conf('rewrite-js', "realpath(__DIR__ . '/../includes/csrf-magic/csrf-magic.js')");
}

// Check for session timeout or renegotiation
session_check();

// Check if access is authorized
if (!isset($_SESSION["access"]) || $_SESSION["access"] != "granted")
{
    set_unauthenticated_redirect();
    header("Location: ../index.php");
    exit(0);
}

// Enforce that the user has access to governance
enforce_permission_governance();

// Check if a new document was submitted
if (isset($_POST['add_document']))
{
      $document_type = $_POST['document_type'];
      $document_name = $_POST['document_name'];
      $parent        = $_POST['parent'];
      $status        = $_POST['status'];
      $creation_date = $_POST['creation_date'];
      $creation_date = get_standard_date_from_default_format($creation_date);

      // Check if the document name is null
      if (!$document_type || !$document_name)
      {
            // Display an alert
            set_alert(true, "bad", "The document name cannot be empty.");
      }
      // Otherwise
      else
      {
            if(empty($_SESSION['add_documentation']))
            {
                // Display an alert
                set_alert(true, "bad", $escaper->escapeHtml($lang['NoAddDocumentationPermission']));
            }
            // Insert a new document
            elseif($errors = add_document($document_type, $document_name, $parent, $status, $creation_date))
            {
                // Display an alert
                set_alert(true, "good", $escaper->escapeHtml($lang['DocumentAdded']));
            }
      }
      refresh();
}

// Check if a document was submitted to update
if (isset($_POST['update_document']))
{
      $id            = $_POST['document_id'];
      $document_type = $_POST['document_type'];
      $document_name = $_POST['document_name'];
      $parent        = (int)$_POST['parent'];
      $status        = $_POST['status'];
      $creation_date = $_POST['creation_date'];
      $creation_date = get_standard_date_from_default_format($creation_date);

      // Check if the document name is null
      if (!$document_type || !$document_name)
      {
            // Display an alert
            set_alert(true, "bad", "The document name cannot be empty.");
      }
      // Otherwise
      else
      {
            if(empty($_SESSION['modify_documentation']))
            {
                // Display an alert
                set_alert(true, "bad", $escaper->escapeHtml($lang['NoModifyDocumentationPermission']));
            }
            // Update document
            elseif($errors = update_document($id, $document_type, $document_name, $parent, $status, $creation_date))
            {
                // Display an alert
                set_alert(true, "good", $escaper->escapeHtml($lang['DocumentAdded']));
            }
      }
      refresh();
}

// Check if a document was submitted to update
if (isset($_POST['delete_document']))
{
    $id           = $_POST['document_id'];
    $version      = $_POST['version'];
      
    if(empty($_SESSION['delete_documentation']))
    {
        // Display an alert
        set_alert(true, "bad", $escaper->escapeHtml($lang['NoDeleteDocumentationPermission']));
    }
    // Delete documents
    elseif($errors = delete_document($id, $version))
    {
        // Display an alert
        set_alert(true, "good", $escaper->escapeHtml($lang['DocumentDeleted']));
    }
    refresh();
}


?>

<!DOCTYPE html>
<html ng-app="SimpleRisk">
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/head.php'); ?>
<body>

       
  <?php
      view_top_menu("Governance");

      // Get any alert messages
      get_alert();
  ?>

  <div class="container">
    <div class="row">
      <div class="col-3">
        <?php view_governance_menu("DocumentProgram"); ?>
      </div>
      <div class="col-9">
        <div id="show-alert"></div>
        <div class="row">
          <div class="col-12">
            <!--  Documents container Begin -->
            <div id="documents-tab-content" class="plan-projects tab-data hide">

              <div class="status-tabs" >

                <?php 
                    if($_SESSION['add_documentation'])
                    {
                        echo "<a href=\"#document-program--add\" id=\"document-add-btn\" role=\"button\" data-toggle=\"modal\" class=\"project--add\"><i class=\"fa fa-plus\"></i></a>";
                    }
                ?>
                

                <ul class="clearfix tabs-nav">
                  <li><a href="#document-hierachy-content" class="status" data-status="1"><?php echo $escaper->escapeHtml($lang['DocumentHierarchy']); ?></a></li>
                  <li><a href="#policies-content" class="status" data-status="2"><?php echo $escaper->escapeHtml($lang['Policies']); ?> </a></li>
                  <li><a href="#guidelines-content" class="status" data-status="2"><?php echo $escaper->escapeHtml($lang['Guidelines']); ?> </a></li>
                  <li><a href="#standards-content" class="status" data-status="2"><?php echo $escaper->escapeHtml($lang['Standards']); ?> </a></li>
                  <li><a href="#procedures-content" class="status" data-status="2"><?php echo $escaper->escapeHtml($lang['Procedures']); ?> </a></li>
                </ul>

                  <div id="document-hierachy-content" class="custom-treegrid-container">
                        <?php get_document_hierarchy_tabs() ?>
                  </div>
                  <div id="policies-content" class="custom-treegrid-container">
                        <?php get_document_tabular_tabs("policies") ?>
                  </div>
                  <div id="guidelines-content" class="custom-treegrid-container">
                        <?php get_document_tabular_tabs("guidelines") ?>
                  </div>
                  <div id="standards-content" class="custom-treegrid-container">
                        <?php get_document_tabular_tabs("standards") ?>
                  </div>
                  <div id="procedures-content" class="custom-treegrid-container">
                        <?php get_document_tabular_tabs("procedures") ?>
                  </div>
              </div> <!-- status-tabs -->

            </div>
            <!-- Documents container Ends -->

            
          </div>
        </div>
      </div>
    </div>
  </div>
          
    <!-- MODAL WINDOW FOR ADDING DOCUMENT -->
    <div id="document-program--add" class="modal hide fade" tabindex="-1" role="dialog">
      <div class="modal-body">
        <form class="" action="#" method="post" autocomplete="off" enctype=multipart/form-data>
          <div class="form-group">
            <label for=""><?php echo $escaper->escapeHtml($lang['DocumentType']); ?></label>
            <select required="" class="document_type" name="document_type">
                <option value="">--</option>
                <option value="policies"><?php echo $escaper->escapeHtml($lang['Policies']); ?></option>
                <option value="guidelines"><?php echo $escaper->escapeHtml($lang['Guidelines']); ?></option>
                <option value="standards"><?php echo $escaper->escapeHtml($lang['Standards']); ?></option>
                <option value="procedures"><?php echo $escaper->escapeHtml($lang['Procedures']); ?></option>
            </select>
            <label for=""><?php echo $escaper->escapeHtml($lang['DocumentName']); ?></label>
            <input required="" type="text" name="document_name" id="document_name" value="" class="form-control" />
            <label for=""><?php echo $escaper->escapeHtml($lang['CreationDate']); ?></label>
            <input type="text" class="form-control datepicker" name="creation_date">
            <label for=""><?php echo $escaper->escapeHtml($lang['ParentDocument']); ?></label>
            <div class="parent_documents_container">
                <select>
                    <option>--</option>
                </select>
            </div>
            <label for=""><?php echo $escaper->escapeHtml($lang['Status']); ?></label>
            <select name="status">
                <option value="Draft"><?php echo $escaper->escapeHtml($lang['Draft']) ?></option>
                <option value="InReview"><?php echo $escaper->escapeHtml($lang['InReview']) ?></option>
                <option value="Approved"><?php echo $escaper->escapeHtml($lang['Approved']) ?></option>
            </select>
            <div class="file-uploader">
                <label for="file-upload" class="btn">Choose File</label>
                <font size="2"><strong>Max <?php echo round(get_setting('max_upload_size')/1024/1024); ?> Mb</strong></font>
                <input required="" type="file" id="file-upload" name="file[]" class="hidden-file-upload active" />
            </div>
          </div>
          <br>
          
          <div class="form-group text-right">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
            <button type="submit" name="add_document" class="btn btn-danger"><?php echo $escaper->escapeHtml($lang['Add']); ?></button>
          </div>
        </form>
      </div>
    </div>
    
    <!-- MODEL WINDOW FOR ADDING DOCUMENT -->
    <div id="document-update-modal" class="modal hide fade" tabindex="-1" role="dialog">
      <div class="modal-body">
        <form class="" action="#" method="post" autocomplete="off" enctype=multipart/form-data>
          <div class="form-group">
            <label for=""><?php echo $escaper->escapeHtml($lang['DocumentType']); ?></label>
            <select required="" class="document_type" name="document_type">
                <option value="">--</option>
                <option value="policies"><?php echo $escaper->escapeHtml($lang['Policies']); ?></option>
                <option value="guidelines"><?php echo $escaper->escapeHtml($lang['Guidelines']); ?></option>
                <option value="standards"><?php echo $escaper->escapeHtml($lang['Standards']); ?></option>
                <option value="procedures"><?php echo $escaper->escapeHtml($lang['Procedures']); ?></option>
            </select>
            <label for=""><?php echo $escaper->escapeHtml($lang['DocumentName']); ?></label>
            <input required="" type="text" name="document_name" id="document_name" value="" class="form-control" />
            <label for=""><?php echo $escaper->escapeHtml($lang['CreationDate']); ?></label>
            <input type="text" class="form-control datepicker" name="creation_date">
            <label for=""><?php echo $escaper->escapeHtml($lang['ParentDocument']); ?></label>
            <div class="parent_documents_container">
                <select>
                    <option>--</option>
                </select>
            </div>
            <label for=""><?php echo $escaper->escapeHtml($lang['Status']); ?></label>
            <select name="status">
                <option value="Draft"><?php echo $escaper->escapeHtml($lang['Draft']) ?></option>
                <option value="InReview"><?php echo $escaper->escapeHtml($lang['InReview']) ?></option>
                <option value="Approved"><?php echo $escaper->escapeHtml($lang['Approved']) ?></option>
            </select>
            <input type="hidden" name="document_id" value="">
            <div class="file-uploader">
                <label for="file-upload-update" class="btn">Choose File</label>
                <font size="2"><strong>Max <?php echo round(get_setting('max_upload_size')/1024/1024); ?> Mb</strong></font>
                <input type="file" id="file-upload-update" name="file[]" class="hidden-file-upload active" />
            </div>
          </div>
          <br>
          
          <div class="form-group text-right">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
            <button type="submit" name="update_document" class="btn btn-danger"><?php echo $escaper->escapeHtml($lang['Update']); ?></button>
          </div>
        </form>
      </div>
    </div>
    
    <!-- MODEL WINDOW FOR DOCUMENT DELETE CONFIRM -->
    <div id="document-delete-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-body">

        <form action="" method="post">
          <div class="form-group text-center">
            <label for=""><?php echo $escaper->escapeHtml($lang['AreYouSureYouWantToDeleteThisDocument']); ?></label>
            <input type="hidden" class="document_id" name="document_id" value="" />
            <input type="hidden" class="version" name="version" value="" />
          </div>

          <div class="form-group text-center control-delete-actions">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $escaper->escapeHtml($lang['Cancel']); ?></button>
            <button type="submit" name="delete_document" class="btn btn-danger"><?php echo $escaper->escapeHtml($lang['Yes']); ?></button>
          </div>
        </form>

      </div>
    </div>
    
    <?php display_set_default_date_format_script(); ?>

<?php include_once($_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'); ?>
</body>
</html>
