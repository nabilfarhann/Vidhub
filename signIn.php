<?php require_once("includes/header.php"); ?>
<?php 
require_once("includes/config.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/FormSanitizer.php");

$account = new Account($con);

if(isset($_POST["submitButton"])){
    $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);

    $wasSuccessful = $account->login($username, $password);

    if($wasSuccessful){
        $_SESSION["userLoggedIn"] = $username;
        header("Location: index");
    }
}

function getInputValue($name){
    if(isset($_POST[$name])){
        echo $_POST[$name];
    }
}

if($usernameLoggedIn){
    $username = $userLoggedInObj->getUsername();
    header ("Location: profile?username=$username");
}

?>

	<!-- ##### Login Area Start ##### -->
    <div class="vizew-login-area section-padding-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6">
                    <div class="login-content">
                        <!-- Section Title -->
                        <div class="section-heading">
                            <h4>Great to have you back!</h4>
                            <div class="line"></div>
                        </div>

                        <form action="signIn" method="POST">
                            <div class="form-group">
                                <?php echo $account->getError(Constants::$loginFailed); ?>
                                <input type="text" class="form-control" name="username" placeholder="User Name" required autocomplete="off" value="<?php getInputValue('username'); ?>">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="customControlAutosizing">
                                    <label class="custom-control-label" for="customControlAutosizing">Remember me</label>
                                </div>
                            </div>
                            <button type="submit" name="submitButton" class="btn vizew-btn w-100 mt-30">Login</button>
                            <a href="signUp" class="btn vizew-btnregister w-100 mt-30">Register</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ##### Login Area End ##### -->

<?php require_once("includes/footer.php"); ?>