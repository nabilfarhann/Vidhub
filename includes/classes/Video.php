<?php

class Video{

	private $con, $sqlData, $userLoggedInObj;

	public function __construct($con, $input, $userLoggedInObj){
		$this->con = $con;
		$this->userLoggedInObj = $userLoggedInObj;

		if(is_array($input)){
			$this->sqlData = $input;
		} else{
			$query = $this->con->prepare("SELECT * FROM videos WHERE id = :id");
			$query->bindParam(":id", $input);
			$query->execute();

			$this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
		}		
	}

	public function getId(){
		return $this->sqlData["id"];
	}

	public function getUploadedBy(){
		return $this->sqlData["uploadedBy"];
	}

	public function getTitle(){
		return $this->sqlData["title"];
	}

	public function getDescription(){
		return $this->sqlData["description"];
	}

	public function getPrivacy(){
		return $this->sqlData["privacy"];
	}

	public function getFilePath(){
		return $this->sqlData["filePath"];
	}

	public function getCategory(){
		$categoryId = $this->sqlData["category"];
		$query = $this->con->prepare("SELECT name FROM categories WHERE id=:id");
		$query->bindParam(":id", $categoryId);
		$query->execute();

		return $query->fetchColumn();
	}

	public function getUploadDate(){
		$date = $this->sqlData["uploadDate"];
		return date("M j, Y", strtotime($date));
	}

	public function getTimeStamp(){
		$date = $this->sqlData["uploadDate"];
		return date("M jS, Y", strtotime($date));
	}

	public function getViews(){
		return $this->sqlData["views"];
	}

	public function getDuration(){
		return $this->sqlData["duration"];
	}

	public function incrementViews(){
		$query = $this->con->prepare("UPDATE videos SET views=views+1 WHERE id=:id");
		$query->bindParam(":id", $videoId);

		$videoId = $this->getId();
		$query->execute();

		$this->sqlData["views"] = $this->sqlData["views"] + 1;
	}

	public function getLikes(){
		$query = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE videoId = :videoId");
		$query->bindParam(":videoId", $videoId);
		$videoId = $this->getId();
		$query->execute();

		$data = $query->fetch(PDO::FETCH_ASSOC);
		return $data["count"];
	}

	public function getDislikes(){
		$query = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE videoId = :videoId");
		$query->bindParam(":videoId", $videoId);
		$videoId = $this->getId();
		$query->execute();

		$data = $query->fetch(PDO::FETCH_ASSOC);
		return $data["count"];
	}

	public function like(){
		$id = $this->getId();
		$username = $this->userLoggedInObj->getUsername();

		if($this->wasLikedBy()){
			// User has already liked
			$query = $this->con->prepare("DELETE FROM likes WHERE username=:username AND videoId=:videoId");
			$query->bindParam(":username", $username);
			$query->bindParam(":videoId", $id);
			$query->execute();

			$result = array(
				"likes" => -1,
				"dislikes" => 0
			);
			return json_encode($result);
		}else{
			$query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username AND videoId=:videoId");
			$query->bindParam(":username", $username);
			$query->bindParam(":videoId", $id);
			$query->execute();
			$count = $query->rowCount();

			$query = $this->con->prepare("INSERT INTO likes(username, videoId) VALUES(:username, :videoId)");
			$query->bindParam(":username", $username);
			$query->bindParam(":videoId", $id);
			$query->execute();

			$result = array(
				"likes" => 1,
				"dislikes" => 0 - $count
			);
			return json_encode($result);
		}
	}

	public function dislike(){
		$id = $this->getId();
		$username = $this->userLoggedInObj->getUsername();

		if($this->wasDislikedBy()){
			// User has already liked
			$query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username AND videoId=:videoId");
			$query->bindParam(":username", $username);
			$query->bindParam(":videoId", $id);
			$query->execute();

			$result = array(
				"likes" => 0,
				"dislikes" => -1
			);
			return json_encode($result);
		}else{
			$query = $this->con->prepare("DELETE FROM likes WHERE username=:username AND videoId=:videoId");
			$query->bindParam(":username", $username);
			$query->bindParam(":videoId", $id);
			$query->execute();
			$count = $query->rowCount();

			$query = $this->con->prepare("INSERT INTO dislikes(username, videoId) VALUES(:username, :videoId)");
			$query->bindParam(":username", $username);
			$query->bindParam(":videoId", $id);
			$query->execute();

			$result = array(
				"likes" => 0 - $count,
				"dislikes" => 1
			);
			return json_encode($result);
		}
	}

	public function wasLikedBy(){
		$id = $this->getId();

		$query = $this->con->prepare("SELECT * FROM likes WHERE username=:username AND videoId=:videoId");
		$query->bindParam(":username", $username);
		$query->bindParam(":videoId", $id);

		$username = $this->userLoggedInObj->getUsername();
		$query->execute();

		return $query->rowCount() > 0;
	}

	public function wasDislikedBy(){
		$id = $this->getId();

		$query = $this->con->prepare("SELECT * FROM dislikes WHERE username=:username AND videoId=:videoId");
		$query->bindParam(":username", $username);
		$query->bindParam(":videoId", $id);

		$username = $this->userLoggedInObj->getUsername();
		$query->execute();

		return $query->rowCount() > 0;
	}

	public function getNumberOfComments(){
		$query = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId");
		$query->bindParam(":videoId", $id);

		$id = $this->getId();
		$query->execute();

		return $query->rowCount();
	}

	public function getComments(){
		$query = $this->con->prepare("SELECT * FROM comments WHERE videoId=:videoId AND responseTo=0 ORDER BY datePosted DESC");
		$query->bindParam(":videoId", $id);

		$id = $this->getId();
		$query->execute();

		$comments = array();
		
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$comment = new Comment($this->con, $row, $this->userLoggedInObj, $id);
			array_push($comments, $comment);
		}

		return $comments;
	}

	public function getThumbnail(){
		$query = $this->con->prepare("SELECT filePath FROM thumbnails WHERE videoId=:videoId AND selected=1");
		$query->bindParam(":videoId", $videoId);
		$videoId = $this->getId();
		$query->execute();

		return $query->fetchColumn();
	}

	public function getNewVideo(){
		$query = $this->con->prepare("SELECT * FROM videos ORDER BY uploadDate DESC LIMIT 5");
		$query->execute();
		$count = $query->rowCount();

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$datas[] = $row;
		}

		if(!$count){
			echo "No Video";
		}else{
			foreach($datas as $data){
				$output = $data['title'];
				$url = "watch.php?id=" . $data['id'];
				echo "<li><a href='$url'>$output</a></li>";
			}
		}
	}
}

?>