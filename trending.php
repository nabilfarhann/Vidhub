<?php
require_once("includes/header.php");
require_once("includes/classes/TrendingProvider.php");

$trendingProvider = new TrendingProvider($con, $userLoggedInObj);
$videos = $trendingProvider->getVideos();

$videoGrid = new VideoGrid($con, $userLoggedInObj);
?>

<div class="container">
	<div class="largeVideoGridContainer">
		<?php
			if(sizeof($videos) > 0){
				echo $videoGrid->createLarge($videos, "Trending videos uploaded in the last week", false);
			}else{
				echo "No trending videos to show";
			}
		?>
	</div>
</div>

<?php
require_once("includes/footer.php");
?>