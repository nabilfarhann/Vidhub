<?php
require_once("ButtonProvider.php"); 
class CommentControls {

    private $con, $comment, $userLoggedInObj;

    public function __construct($con, $comment, $userLoggedInObj) {
        $this->con = $con;
        $this->comment = $comment;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {

        $replyButton = $this->createReplyButton();
        $likesCount = $this->createLikesCount();
        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDislikeButton();
        $replySection = $this->createReplySection();

        return "<div class='post-meta d-flex align-items-center'>
                    $replyButton
                    $likesCount
					$likeButton
                    $dislikeButton
				</div>
                $replySection";
    }

    private function createReplyButton(){
        $text = "Reply";
        $action = "toggleReply(this)";

        return ButtonProvider::createButton($text, null, $action, "align-items-center");
    }

    private function createLikesCount(){
        $text = $this->comment->getLikes();

        if($text == 0){
            $text = "";
        }

        return "<span class='likesCount align-items-center'>$text</span>";
    }

    private function createReplySection(){
        $postedBy = $this->userLoggedInObj->getUsername();
        $videoId = $this->comment->getVideoId();
        $commentId = $this->comment->getId();

        $profileButton = ButtonProvider::createUserProfileButton($this->con, $postedBy);

        $cancelButtonAction = "toggleReply(this)";
        $cancelButton = ButtonProvider::createButton("Cancel", null, $cancelButtonAction, "cancelComment");

        $postButtonAction = "postComment(this, \"$postedBy\", $videoId, $commentId, \"repliesSection\")";
        $postButton = ButtonProvider::createButton("Reply", null, $postButtonAction, "postComment");

        return "<div class='commentForm hidden'>
                    $profileButton
                    <textarea class='commentBodyClass' placeholder='Add a public comment'></textarea>
                    $cancelButton
                    $postButton
                </div>";
    }

    private function createLikeButton() {
    	$commentId = $this->comment->getId();
        $videoId = $this->comment->getVideoId();
    	$action = "likeComment($commentId, this, $videoId)";
    	$class = "likeButton";

    	$imageSrc = "img/icons/thumb-up.png";

    	if($this->comment->wasLikedBy()){
    		$imageSrc = "img/icons/thumb-up-active.png";
    	}


        return ButtonProvider::createButton("", $imageSrc, $action, $class);
    }

    private function createDislikeButton() {
        $commentId = $this->comment->getId();
    	$videoId = $this->comment->getVideoId();
    	$action = "dislikeComment($commentId, this, $videoId)";
    	$class = "dislikeButton";

    	$imageSrc = "img/icons/thumb-down.png";

    	if($this->comment->wasDislikedBy()){
    		$imageSrc = "img/icons/thumb-down-active.png";
    	}


        return ButtonProvider::createButton("", $imageSrc, $action, $class);
    }
}
?>