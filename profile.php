<?php
require_once("includes/header.php");
require_once("includes/classes/ProfileGenerator.php");
if(isset($_GET["username"])){
	$profileUsername = $_GET["username"];
}else{
	echo "Channel not found";
	exit();
}

$profileGenerator = new ProfileGenerator($con, $userLoggedInObj, $profileUsername);
echo $profileGenerator->create();
?>
<script src="js/userActions.js"></script>

<?php
require_once("includes/footer.php");
?>