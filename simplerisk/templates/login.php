
<!-- start ./templates/login.php -->
<div class="container p-5">
    <div class="row">
        <div class="col-lg-4 col-8 mx-auto rounded bg-light shadow">
                <form name="authenticate" method="post" action="" class="p-5">
                    <h3><?php echo $escaper->escapeHtml($lang['LogInHere']) ?></h3>
                    <div class="form-group">
                        <label for="user"><?php echo $escaper->escapeHtml($lang['Username']) ?></label>
                        <input class="form-control" name="user" id="user" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="pass"><?php echo $escaper->escapeHtml($lang['Password']) ?></label>
                        <input class="form-control input-medium" name="pass" id="pass" type="password" autocomplete="off" />
                    </div>
                    <?php 
                    // If the custom authentication extra is enabled
                    if (custom_authentication_extra())
                    {
                        // If SSO Login is enabled or not set yet
                        if(get_settting_by_name("GO_TO_SSO_LOGIN") === false || get_settting_by_name("GO_TO_SSO_LOGIN") === '1')
                        {
                            // Display the SSO login link
                            echo "<div class=\"form-group\"><a href=\"extras/authentication/login.php\">" . $escaper->escapeHtml($lang['GoToSSOLoginPage']) . "</div>\n";
                        }
                    }
                    ?>
                    <button type="submit" name="submit" class="btn btn-danger btn-block"><?php echo $escaper->escapeHtml($lang['Login']) ?></button>
                    <a class="btn btn-link btn-block" href="reset.php"><?php echo $escaper->escapeHtml($lang['ForgotYourPassword']) ?></a>
                    
                </form>
        </div>
    </div>
</div>
<!-- end ./templates/login.php -->
