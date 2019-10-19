<?php
require_once("includes/classes/VideoInfoControls.php");
class VideoInfoSection {

    private $con, $video, $userLoggedInObj;

    public function __construct($con, $video, $userLoggedInObj) {
        $this->con = $con;
        $this->video = $video;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {
        return $this->createPrimaryInfo();
    }

    public function createSecondary() {
        return $this->createSecondaryInfo();
    }

    public function createThird(){
    	return $this->createThirdInfo();
    }

    private function createPrimaryInfo() {
        $uploadedBy = $this->video->getUploadedBy();
        $uploadDate = $this->video->getUploadDate();

        $videoInfoControls = new VideoInfoControls($this->video, $this->userLoggedInObj);
        $controls = $videoInfoControls->create();

        return "<div class='post-meta d-flex align-items-center'>
					<a href='profile?username=$uploadedBy'' class='post-author'>By $uploadedBy</a>
					<a href='#'' class='post-date'>$uploadDate</a>
				</div>
				$controls";
    }

    private function createSecondaryInfo() {
    	$uploadedBy = $this->video->getUploadedBy();
        $profileButton = ButtonProvider::createUserProfileButton($this->con, $uploadedBy);
        // $postCategory = "";

        return $profileButton;
    }

    private function createThirdInfo(){
    	$uploadedBy = $this->video->getUploadedBy();

    	if($uploadedBy == $this->userLoggedInObj->getUsername()){
    		$actionButton = ButtonProvider::createEditVideoButton($this->video->getId());
    	}else{
            $userToObject = new User($this->con, $uploadedBy);
    		$actionButton = ButtonProvider::createSubscriberButton($this->con, $userToObject, $this->userLoggedInObj);
    	}

    	return "<div class='post-author-desc pl-4'>
					<a href='profile?username=$uploadedBy'' class='author-name'>$uploadedBy</a>
					<p class='noselect'>Hello! Please subscribe my channel to get new video updates.</p>
					$actionButton
				</div>";
    }
}
?>