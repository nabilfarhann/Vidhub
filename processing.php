<?php
require_once("includes/header.php");
require_once("includes/footer.php");
require_once("includes/classes/VideoUploadData.php");
require_once("includes/classes/VideoProcessor.php");

if(!isset($_POST["uploadButton"])){
	echo "No file sent to the page";
	exit();
}

//1) create file upload data
$videoUploadData = new VideoUploadData($_POST["video_url"], $_POST["video_thumbnail"], $_POST["cloud_id"], $_POST["duration"], $_POST["titleInput"], $_POST["descriptionInput"], $_POST["privacyInput"], $_POST["categoryInput"], $userLoggedInObj->getUsername());

//2) process video data (upload)
$videoProcessor = new VideoProcessor($con);
$wasSuccessful = $videoProcessor->upload($videoUploadData);

//3) check if upload was successful
if($wasSuccessful){
	//echo "Upload successful";
	echo "<div class='container' style='margin-top: 10px;'>
			Video has been successfully uploaded.
			</div>";
}

?>