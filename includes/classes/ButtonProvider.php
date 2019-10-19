<?php
class ButtonProvider{

	public static $signInFunction = "notSignedIn()";

    public static function createLink($link) {
        return User::isLoggedIn() ? $link : ButtonProvider::$signInFunction;
    }
    
	public static function createButton($text, $imageSrc, $action, $class){

		$image = ($imageSrc == null) ? "" : "<img src='$imageSrc'>";

		$action  = ButtonProvider::createLink($action);

		return "<button class='$class' onclick='$action'>
					$image
					<span class='text'>$text</span>
				</button>";
	}

	public static function createHyperlinkEditButton($text, $href){

		return "<a href='$href' class='btn subscribe-btn'>
					<i class='fa fa-edit' aria-hidden='true'></i> $text
				</a>";
	}

	public static function createUserProfileButton($con, $username){
		$userObj = new User($con, $username);
		$profilePic = $userObj->getProfilePic();
		$link = "profile?username=$username";

		return "<div class='post-author-thumb'>
					<a href='$link'>
						<img src='$profilePic'>
					</a>
				</div>";
	}

	public static function createEditVideoButton($videoId){
		$href = "editVideo?videoId=$videoId";

		$button = ButtonProvider::createHyperlinkEditButton("Edit Video", $href);

		return "$button";
	}

	public static function createSubscriberButton($con, $userToObj, $userLoggedInObj){
		$userTo = $userToObj->getUsername();
		$userLoggedIn = $userLoggedInObj->getUsername();

		$isSubscribedTo = $userLoggedInObj->isSubscribedTo($userTo);
		$buttonText = $isSubscribedTo ? "Subscribed" : "Subscribe";
		$buttonText .= " " . $userToObj->getSubscriberCount();

		$buttonClass = $isSubscribedTo ? "unsubscribe button" : "subscribe";
		$action = "subscribe(\"$userTo\", \"$userLoggedIn\", this)";

		$button = ButtonProvider::createButton($buttonText, null, $action, $buttonClass);

		return $button;
	}
}
?>