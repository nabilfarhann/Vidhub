<?php
class VideoUploadData{

	public $videoDataArray, $title, $description, $privacy, $category, $uploadedBy;

	public function __construct($videoUrl, $videoThumbnail, $videoCloudId, $duration, $title, $description, $privacy, $category, $uploadedBy){
		$this->videoUrl = $videoUrl;
		$this->videoThumbnail = $videoThumbnail;
		$this->videoCloudId = $videoCloudId;
		$this->duration = $duration;
		$this->title = $title;
		$this->description = $description;
		$this->privacy = $privacy;
		$this->category = $category;
		$this->uploadedBy = $uploadedBy;
	}

	public function updateDetails($con, $videoId){
		$query = $con->prepare("UPDATE videos SET title=:title, description=:description, privacy=:privacy, category=:category WHERE id=:videoId");
		$query->bindParam(":title", $this->title);
		$query->bindParam(":description", $this->description);
		$query->bindParam(":privacy", $this->privacy);
		$query->bindParam(":category", $this->category);
		$query->bindParam(":videoId", $videoId);

		return $query->execute();
	}

	public function deleteVideo($con, $videoId){
		$query = $con->prepare("DELETE FROM videos WHERE id=:videoId");
		$query->bindParam(":videoId", $videoId);
		$query2 = $con->prepare("DELETE FROM thumbnails WHERE videoId=:videoId");
		$query2->bindParam(":videoId", $videoId);
		$query3 = $con->prepare("DELETE FROM likes WHERE videoId=:videoId");
		$query3->bindParam(":videoId", $videoId);
		$query4 = $con->prepare("DELETE FROM dislikes WHERE videoId=:videoId");
		$query4->bindParam(":videoId", $videoId);
		$query5 = $con->prepare("DELETE FROM comments WHERE videoId=:videoId");
		$query5->bindParam(":videoId", $videoId);

		$query2->execute();
		$query3->execute();
		$query4->execute();
		$query5->execute();
		
		return $query->execute();
	}

}

?>