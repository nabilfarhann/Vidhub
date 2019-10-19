<?php
require_once("includes/header.php");

if(!User::isLoggedIn()){
	header("Location: signIn");
}

$subscriptionsProvider = new SubscriptionsProvider($con, $userLoggedInObj);
$videos = $subscriptionsProvider->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class="container">
	<div class="largeVideoGridContainer">
		<?php
			if(sizeof($videos) > 0){
				echo $videoGrid->createLarge($videos, "New from your subscriptions", false);
			}else{
				echo "No videos to show";
			}
		?>
	</div>
</div>

<?php
require_once("includes/footer.php");
?>