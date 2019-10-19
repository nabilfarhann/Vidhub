<?php
class VideoProcessor{
	private $con;
	private $sizeLimit = 500000000; //0.5GB
	private $allowedTypes = array("mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg", "MP4");
	private $ffmpegPath;
	private $ffprobe;

	public function __construct($con){
		$this->con = $con;
		// $this->ffmpegPath = realpath("ffmpeg/bin/ffmpeg.exe");
		// $this->ffprobePath = realpath("ffmpeg/bin/ffprobe.exe");
	}

	public function upload($videoUploadData){

		if(!$this->insertVideoData($videoUploadData)){
			echo "Insert query failed";
			return false;
		}

		if(!$this->generateThumbnails($videoUploadData)){
			echo "Upload failed - could not generate thumbnails\n";
			return false;
		}
		return true;

		// $targetDir = "uploads/videos/";
		// $videoData = $videoUploadData->videoDataArray;

		// $tempFilePath = $targetDir . uniqid() . basename($videoData["name"]);

		// $tempFilePath = str_replace(" ", "_", $tempFilePath);

		// $isValidData = $this->processData($videoData, $tempFilePath);

		// if(!$isValidData){
		// 	return false;
		// }

		// if(move_uploaded_file($videoData["tmp_name"], $tempFilePath)){
		// 	$finalFilePath = $targetDir . uniqid() . ".mp4";

		// 	if(!$this->insertVideoData($videoUploadData, $finalFilePath)){
		// 		echo "Insert query failed";
		// 		return false;
		// 	}

		// 	if(!$this->convertVideoToMp4($tempFilePath, $finalFilePath)){
		// 		echo "Upload failed";
		// 		return false;
		// 	}

		// 	if(!$this->deleteFile($tempFilePath)){
		// 		echo "Upload failed";
		// 		return false;
		// 	}

		// 	if(!$this->generateThumbnails($finalFilePath)){
		// 		echo "Upload failed - could not generate thumbnails\n";
		// 		return false;
		// 	}

		// 	return true;
		// }
	}

	// private function processData($videoData, $filePath){
	// 	$videoType = pathInfo($filePath, PATHINFO_EXTENSION);

	// 	if(!$this->isValidSize($videoData)){
	// 		echo "File too large. Can't be more than " . $this->sizeLimit . " bytes";
	// 		return false;
	// 	}
	// 	else if(!$this->isValidType($videoType)){
	// 		echo "Invalid file type ";
	// 		return false;
	// 	}
	// 	else if($this->hasError($videoData)){
	// 		echo "Error code: " . $videoData["error"];
	// 		return false;
	// 	}

	// 	return true;
	// }

	// private function isValidSize($data){
	// 	return $data["size"] <= $this->sizeLimit;
	// }

	// private function isValidType($type){
	// 	$lowercased = strtolower($type);
	// 	return in_array($lowercased, $this->allowedTypes);
	// }

	// private function hasError($data){
	// 	return $data["error"] != 0;
	// }

	private function insertVideoData($uploadData){
		$durations = $uploadData->duration;

		$hours = floor($durations / 3600);
		$mins = floor(($durations - ($hours*3600)) / 60);
		$secs = floor($durations % 60);

		$hours = ($hours < 1) ? "" : $hours . ":";
		$mins = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
		$secs = ($secs < 10) ? "0" . $secs  : $secs;

		$durations = $hours.$mins.$secs;

		$query = $this->con->prepare("INSERT INTO videos(title, uploadedBy, description, privacy, category, filePath, duration)
										VALUES(:title, :uploadedBy, :description, :privacy, :category, :filePath, :duration)");

		$query->bindParam(":title", $uploadData->title);
		$query->bindParam(":uploadedBy", $uploadData->uploadedBy);
		$query->bindParam(":description", $uploadData->description);
		$query->bindParam(":privacy", $uploadData->privacy);
		$query->bindParam(":category", $uploadData->category);
		$query->bindParam(":filePath", $uploadData->videoUrl);
		$query->bindParam(":duration", $durations);

		return $query->execute();
	}

	// public function convertVideoToMp4($tempFilePath, $finalFilePath){
	// 	$cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";

	// 	$outputLog = array();
	// 	exec($cmd, $outputLog, $returnCode);

	// 	if($returnCode != 0){
	// 		// Command failed
	// 		foreach($outputLog as $line){
	// 			echo $line . "<br>";
	// 		}
	// 		return false;
	// 	}

	// 	return true;
	// }

	private function deleteFile($filePath){
		if(!unlink($filePath)){
			echo "Could not delete file\n";
			return false;
		}
		return true;
	}

	public function generateThumbnails($videoUploadData){

		$num = 1;
		$selected = $num == 1 ? 1 : 0;
		$videoId = $this->con->lastInsertId();
		$query = $this->con->prepare("INSERT INTO thumbnails(videoId, filePath, selected) VALUES(:videoId, :filePath, :selected)");
		$query->bindParam(":videoId", $videoId);
		$query->bindParam(":filePath", $videoUploadData->videoThumbnail);
		$query->bindParam(":selected", $selected);
		$success = $query->execute();

		if(!$success){
			echo "Error inserting thumbnail\n";
			return false;
		}
		return true;


		// $thumbnailSize = "300x210";
		// $numThumbnails = 10;
		// $pathToThumbnail = "uploads/videos/thumbnails";

		// $duration = $this->getVideoDuration($filePath);

		// $videoId = $this->con->lastInsertId();
		// $this->updateDuration($duration, $videoId);

		// for($num = 1; $num <= $numThumbnails; $num++){
		// 	$imageName = uniqid() . ".jpg";
		// 	$interval = ($duration * 1) / $numThumbnails * $num;
		// 	$fullThumbnailPath = "$pathToThumbnail/$videoId-$imageName";

		// 	$cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1";

		// 	$outputLog = array();
		// 	exec($cmd, $outputLog, $returnCode);

		// 	if($returnCode != 0){
		// 		// Command failed
		// 		foreach($outputLog as $line){
		// 			echo $line . "<br>";
		// 		}
		// 	}

		// 	$query = $this->con->prepare("INSERT INTO thumbnails(videoId, filePath, selected) VALUES(:videoId, :filePath, :selected)");
		// 	$query->bindParam(":videoId", $videoId);
		// 	$query->bindParam(":filePath", $fullThumbnailPath);
		// 	$query->bindParam(":selected", $selected);

		// 	$selected = $num == 1 ? 1 : 0;

		// 	$success = $query->execute();

		// 	if(!$success){
		// 		echo "Error inserting thumbnail\n";
		// 		return false;
		// 	}
		// }

		// return true;
	}

	// private function getVideoDuration($filePath){
	// 	return (int)shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
	// }

	// private function updateDuration($duration, $videoId){

	// 	$hours = floor($duration / 3600);
	// 	$mins = floor(($duration - ($hours*3600)) / 60);
	// 	$secs = floor($duration % 60);

	// 	$hours = ($hours < 1) ? "" : $hours . ":";
	// 	$mins = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
	// 	$secs = ($secs < 10) ? "0" . $secs  : $secs;

	// 	$duration = $hours.$mins.$secs;

	// 	$query = $this->con->prepare("UPDATE videos SET duration=:duration WHERE id=:videoId");
	// 	$query->bindParam(":duration", $duration);
	// 	$query->bindParam(":videoId", $videoId);
	// 	$query->execute();
	// }
}
?>