<?php
class SelectThumbnail{
	private $con, $video;

	public function __construct($con, $video){
		$this->con = $con;
		$this->video = $video;
	}

	public function create(){
		$thumbnailData = $this->getThumbnailData();

		$html = "";

		foreach($thumbnailData as $data){
			$html .= $this->createThumbnailItem($data);
		}

		return "<div class='thumbnailItemsContainer'>
					$html
				</div>";
	}

	private function createThumbnailItem($data){
		$id = $data["id"];
		$url = $data["filePath"];
		$videoId = $data["videoId"];
		$selected = $data["selected"] == 1 ? "selected" : "";

		return "<div class='thumbnailItem $selected' onclick='setNewThumbnail($id, $videoId, this)'>
					<img src='$url'>
				</div>";
	}

	private function getThumbnailData(){
		$data = array();

		$query = $this->con->prepare("SELECT * FROM thumbnails WHERE videoId=:videoId");
		$query->bindParam(":videoId", $videoId);
		$videoId = $this->video->getId();
		$query->execute();

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$data[] = $row;
		}

		return $data;
	}
}
?>