<?php

class VideoDetailsFormProvider{

	private $con;

	public function __construct($con){
		$this->con = $con;
	}

	public function createUploadForm(){
		$fileInput = $this->createFileInput();
		$titleInput = $this->createTitleInput(null);
		$descriptionInput = $this->createDescriptionInput(null);
		$privacyInput = $this->createPrivacyInput(null);
		$categoriesInput = $this->createCategoriesInput(null);
		$uploadButton = $this->createUploadButton();
		return "<form action='processing' method='POST' enctype='multipart/form-data'>
					$fileInput
					$titleInput
					$descriptionInput
					$privacyInput
					$categoriesInput
					$uploadButton
					<input type='hidden' value='' id='video_url' name='video_url'>
            		<input type='hidden' value='' id='video_thumbnail' name='video_thumbnail'>
					<input type='hidden' value='' id='cloud_id' name='cloud_id'>
					<input type='hidden' value='' id='duration' name='duration'>
				</form>";
	}

	public function createEditDetailsForm($video){
		$titleInput = $this->createTitleInput($video->getTitle());
		$descriptionInput = $this->createDescriptionInput($video->getDescription());
		$privacyInput = $this->createPrivacyInput($video->getPrivacy());
		$categoriesInput = $this->createCategoriesInput($video->getCategory());
		$saveButton = $this->createSaveButton();
		$deleteButton = $this->createDeleteButton();
		return "<form method='POST'>
					$titleInput
					$descriptionInput
					$privacyInput
					$categoriesInput
					$saveButton
					$deleteButton
				</form>";
	}

	private function createFileInput(){
		return "<div class='input-group mb-3'>
				  	<div class='custom-file'>
					  <button type='button' class='btn vizew-btns' id='upload_widget_opener' required>Upload Video</button>
				  	</div>
				</div>";
	}

	private function createTitleInput($value){
		if($value == null) $value = "";
		return "<div class='form-group'>
                    <label for='name'>Title</label>
                    <input type='text' class='form-control' id='name' name='titleInput' value='$value' required autocomplete='off'>
                </div>";
	}

	private function createDescriptionInput($value){
		if($value == null) $value = "";
		return "<div class='form-group'>
                    <label for='description'>Description</label>
                    <textarea name='descriptionInput' class='form-control' id='description' cols='10' rows='2'>$value</textarea>
                </div>";
	}

	private function createPrivacyInput($value){
		if($value == null) $value = "";

		$privateSelected = ($value == 1) ? "selected='selected'" : "";
		$publicSelected = ($value == 0) ? "selected='selected'" : "";
		return "<div class='form-group'>
					<select class='form-control' id='sel1' name='privacyInput'>
						<option value='0' $publicSelected>Public</option>
				    	<option value='1' $privateSelected>Private</option>
					</select>
				</div>";
	}

	private function createCategoriesInput($value){
		if($value == null) $value = "";
		$query = $this->con->prepare("SELECT * FROM categories");
		$query->execute();

		$html = "<div class='form-group'>
					<select class='form-control' name='categoryInput'>";

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$id = $row["id"];
			$name = $row["name"];

			$selected = ($id == $value) ? "selected='selected'" : "";

			$html .= "<option value='$id' $selected >$name</option>";
		}

		$html .= "</select>
				</div>";

		return $html;
	}

	private function createUploadButton(){
		return "<button data-toggle='modal' data-target='#loadingModal' class='btn vizew-btn mt-30' type='submit' name='uploadButton'>Upload</button>";
	}

	private function createSaveButton(){
		return "<button data-toggle='modal' data-target='#loadingModal' class='btn vizew-btn mt-30' type='submit' name='saveButton'>Save</button>";
	}

	private function createDeleteButton(){
		return "<button data-toggle='modal' data-target='#loadingModal' class='btn vizew-btn mt-30' type='submit' name='deleteButton'>Delete this video</button>";
	}
}

?>