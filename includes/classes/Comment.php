<?php
require_once("ButtonProvider.php");
require_once("CommentControls.php");
class Comment{

	private $con, $input, $userLoggedInObj, $videoId;

	public function __construct($con, $input, $userLoggedInObj, $videoId){
		if(!is_array($input)){
			$query = $con->prepare("SELECT * FROM comments WHERE id=:id");
			$query->bindParam(":id", $input);
			$query->execute();

			$input = $query->fetch(PDO::FETCH_ASSOC);
		}

		$this->sqlData = $input;
		$this->con = $con;
		$this->userLoggedInObj = $userLoggedInObj;
		$this->videoId = $videoId;
	}

	public function create(){
		$id = $this->sqlData["id"];
		$videoId = $this->getVideoId();
		$body = $this->sqlData["body"];
		$postedBy = $this->sqlData["postedBy"];
		$profileButton = ButtonProvider::createUserProfileButton($this->con, $postedBy);
		$timespan = $this->time_elapsed_string($this->sqlData["datePosted"]);

		$commentControlsObj = new CommentControls($this->con, $this, $this->userLoggedInObj);
		$commentControls = $commentControlsObj->create();

		$numResponses = $this->getNumberOfReplies();

		if($numResponses > 0){
			$viewRepliesText = "<span class='repliesSection viewReplies' onclick='getReplies($id, this, $videoId)'>
									View all $numResponses replies</span>";
		}else{
			$viewRepliesText = "<div class='repliesSection'></div>";
		}

		return "<div class='itemContainer'>
					<div class='comment'>
						$profileButton
						<div class='mainContainer'>
							<div class='commentHeader'>
								<a href='profile?username=$postedBy'>
									<span class='username'>$postedBy</span>
								</a>
								<span class='timestamp'>$timespan</span>
							</div>

							<div class='body'>
								$body
							</div>
						</div>
					</div>
					$commentControls
					$viewRepliesText
				</div>";
	}

	public function getNumberOfReplies(){
		$query = $this->con->prepare("SELECT count(*) FROM comments WHERE responseTo=:responseTo");
		$query->bindParam(":responseTo", $id);
		$id = $this->sqlData["id"];
		$query->execute();

		return $query->fetchColumn();
	}

	function time_elapsed_string($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	public function getId(){
		return $this->sqlData["id"];
	}

	public function getVideoId(){
		return $this->videoId;
	}

	public function wasLikedBy(){
		$id = $this->getId();

		$query = $this->con->prepare("SELECT * FROM likes WHERE username=:username AND commentId=:commentId");
		$query->bindParam(":username", $username);
		$query->bindParam(":commentId", $id);

		$username = $this->userLoggedInObj->getUsername();
		$query->execute();

		return $query->rowCount() > 0;
	}

	public function wasDislikedBy(){
		$id = $this->getId();

		$query = $this->con->prepare("SELECT * FROM dislikes WHERE username=:username AND commentId=:commentId");
		$query->bindParam(":username", $username);
		$query->bindParam(":commentId", $id);

		$username = $this->userLoggedInObj->getUsername();
		$query->execute();

		return $query->rowCount() > 0;
	}

	public function getLikes(){
		$query = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE commentId=:commentId");
		$query->bindParam(":commentId", $commentId);
		$commentId = $this->getId();
		$query->execute();

		$data = $query->fetch(PDO::FETCH_ASSOC);
		$numLikes = $data["count"];

		$query = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE commentId=:commentId");
		$query->bindParam(":commentId", $commentId);
		$query->execute();

		$data = $query->fetch(PDO::FETCH_ASSOC);
		$numDislikes = $data["count"];

		return $numLikes - $numDislikes;
	}

	public function like(){
		$id = $this->getId();
		$username = $this->userLoggedInObj->getUsername();

		if($this->wasLikedBy()){
			// User has already liked
			$query = $this->con->prepare("DELETE FROM likes WHERE username=:username AND commentId=:commentId");
			$query->bindParam(":username", $username);
			$query->bindParam(":commentId", $id);
			$query->execute();

			return -1;
		}else{
			$query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username AND commentId=:commentId");
			$query->bindParam(":username", $username);
			$query->bindParam(":commentId", $id);
			$query->execute();
			$count = $query->rowCount();

			$query = $this->con->prepare("INSERT INTO likes(username, commentId) VALUES(:username, :commentId)");
			$query->bindParam(":username", $username);
			$query->bindParam(":commentId", $id);
			$query->execute();

			return 1 + $count;
		}
	}

	public function dislike(){
		$id = $this->getId();
		$username = $this->userLoggedInObj->getUsername();

		if($this->wasDislikedBy()){
			// User has already liked
			$query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username AND commentId=:commentId");
			$query->bindParam(":username", $username);
			$query->bindParam(":commentId", $id);
			$query->execute();

			return 1;
		}else{
			$query = $this->con->prepare("DELETE FROM likes WHERE username=:username AND commentId=:commentId");
			$query->bindParam(":username", $username);
			$query->bindParam(":commentId", $id);
			$query->execute();
			$count = $query->rowCount();

			$query = $this->con->prepare("INSERT INTO dislikes(username, commentId) VALUES(:username, :commentId)");
			$query->bindParam(":username", $username);
			$query->bindParam(":commentId", $id);
			$query->execute();

			return -1 - $count;
		}
	}

	public function getReplies(){
		$query = $this->con->prepare("SELECT * FROM comments WHERE responseTo=:commentId ORDER BY datePosted ASC");
		$query->bindParam(":commentId", $id);

		$id = $this->getId();
		$query->execute();

		$comments = "";
		$videoId = $this->getVideoId();
		
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$comment = new Comment($this->con, $row, $this->userLoggedInObj, $videoId);
			$comments .= $comment->create();
		}

		return $comments;
	}

}

?>