<?php require_once("includes/config.php");
      require_once("includes/classes/Account.php");
      require_once("includes/classes/Constants.php");
      require_once("includes/classes/FormSanitizer.php");

    $account = new Account($con);

    if(isset($_POST["submitButton"])){
        $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
        $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);

        $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);

        $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);
        $email2 = FormSanitizer::sanitizeFormEmail($_POST["email2"]);

        $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
        $password2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);

        $profileImage=$_FILES["profileImage"]["name"];
        $folder="img/profileImage/";
        $uploadfile = $folder . uniqid() . basename($_FILES['profileImage']['name']);
        move_uploaded_file($_FILES["profileImage"]["tmp_name"], $uploadfile);

        $wasSuccessful = $account->register($firstName, $lastName, $username, $email, $email2, $password, $password2, $uploadfile);

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>VidHub</title>

	<link rel="icon" href="img/core-img/favicon.ico">

	<link rel="stylesheet" href="styles/style.css">

</head>
<body>
	
	<div class="vizew-login-area section-padding-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6">
                    <div class="login-content">
                        <!-- Section Title -->
                        <div class="section-heading">
                        	<a href="index" class="nav-brand"><img src="img/core-img/3.png" alt=""></a>
                        	<br>
                        	<br>
                            <div class="line"></div>
                        </div>
                        
                        <form action="signUp" method="POST" enctype="multipart/form-data">
                            <div class='custom-file'>
                                <input type="file" class="form-control" name="profileImage" accept="image/*" oninvalid="this.setCustomValidity('Profile Picture is required')">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="firstName" placeholder="First Name" required autocomplete="off" value="<?php getInputValue('firstName'); ?>" 
                                	oninvalid="this.setCustomValidity('First Name is required')" oninput="this.setCustomValidity('')">
                                <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="lastName" placeholder="Last Name" value="<?php getInputValue('lastName'); ?>" autocomplete="off">
                                <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="username" placeholder="Username" required autocomplete="off" value="<?php getInputValue('username'); ?>"
                                	oninvalid="this.setCustomValidity('Username is required')" oninput="this.setCustomValidity('')">
                                <?php echo $account->getError(Constants::$usernameCharacters); ?>
                                <?php echo $account->getError(Constants::$usernameTaken); ?>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email" required autocomplete="off" value="<?php getInputValue('email'); ?>"
                                	oninvalid="this.setCustomValidity('Email is required')" oninput="this.setCustomValidity('')">
                                <?php echo $account->getError(Constants::$emailInvalid); ?>
                                <?php echo $account->getError(Constants::$emailTaken); ?>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email2" placeholder="Confirm Email" required autocomplete="off" value="<?php getInputValue('email2'); ?>"
                                	oninvalid="this.setCustomValidity('Please confirm your email')" oninput="this.setCustomValidity('')">
                                <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" required autocomplete="off" 
                                	oninvalid="this.setCustomValidity('Password is required')" oninput="this.setCustomValidity('')">
                                <?php echo $account->getError(Constants::$passwordNotAlphanumeric); ?>
                                <?php echo $account->getError(Constants::$passwordLength); ?>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password2" placeholder="Confirm Password" required autocomplete="off" 
                                	oninvalid="this.setCustomValidity('Please confirm your password')" oninput="this.setCustomValidity('')">
                                <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
                            </div>
                            <button type="submit" name="submitButton" class="btn vizew-btn w-100 mt-30">Sign Up</button>
                            <a href="signIn" class="btn vizew-btnregister w-100 mt-30">Back to Login</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>