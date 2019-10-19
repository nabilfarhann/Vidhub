<?php
require_once("includes/header.php");
require_once("includes/classes/VideoPlayer.php");
require_once("includes/classes/VideoDetailsFormProvider.php");
require_once("includes/classes/VideoUploadData.php");
require_once("includes/classes/SelectThumbnail.php");
require_once("includes/footer.php");

if(!User::isLoggedIn()){
	header("Location: index");
}

if(!isset($_GET["videoId"])){
	echo "<div class='container' style='margin-top: 10px;'>
			No video selected
			</div>";
	exit();
}

$video = new Video($con, $_GET["videoId"], $userLoggedInObj);
if($video->getUploadedBy() != $userLoggedInObj->getUsername()){
	echo "<div class='container' style='margin-top: 10px;'>
			This is not your video
			</div>";
	exit();
}

$detailsMessage = "";
if(isset($_POST["saveButton"])){
	$videoData = new VideoUploadData(
		null,
		$_POST["titleInput"],
		$_POST["descriptionInput"],
		$_POST["privacyInput"],
		$_POST["categoryInput"],
		$userLoggedInObj->getUsername()
	);

	if($videoData->updateDetails($con, $video->getId())){
		$detailsMessage = "<div class='alert alert-success'>
								<strong>SUCCESS!</strong> Details updated successfully!
							</div>";
		$video = new Video($con, $_GET["videoId"], $userLoggedInObj);
	}else{
		$detailsMessage = "<div class='alert alert-danger'>
								<strong>ERROR!</strong> Something went wrong
							</div>";
	}
}

if(isset($_POST["deleteButton"])){
	$videoData = new VideoUploadData(
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null,
		null
	);
	if($videoData->deleteVideo($con, $video->getId())){
		header("Location: index");
	}else{
		echo "<div class='container' style='margin-top: 10px;'>
				There is something error
				</div>";
	}
}
?>

<script src="js/editVideoActions.js"></script>

<div class="container" style="margin-top: 20px;">
	<div class="editVideoContainer column">
		<div class="topSection">
			<?php
				$videoPlayer = new VideoPlayer($video);
				echo $videoPlayer->create(false);

				$selectThumbnail = new SelectThumbnail($con, $video);
				echo $selectThumbnail->create();
			?>
		</div>
		<div class="message">
			<?php echo $detailsMessage; ?>
		</div>
		<div class="bottomSection" style="margin-bottom: 20px;">
			<?php
				$formProvider = new VideoDetailsFormProvider($con);
				echo $formProvider->createEditDetailsForm($video);
			?>
		</div>
	</div>
</div>

<?php
require_once("includes/footer.php");
?>