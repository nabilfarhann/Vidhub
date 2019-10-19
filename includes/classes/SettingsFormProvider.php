<?php

class SettingsFormProvider{

	public function createUserDetailsForm($firstName, $lastName, $email){
		$firstNameInput = $this->createFirstNameInput($firstName);
		$lastNameInput = $this->createLastNameInput($lastName);
		$emailInput = $this->createEmailInput($email);
		$saveButton = $this->createSaveUserDetailsButton();

		return "<form action='settings' method='POST' enctype='multipart/form-data'>
					<span class='title'>User details</span>
					<hr class='lineForm'>
					$firstNameInput
					$lastNameInput
					$emailInput
					$saveButton
				</form>";
	}

	public function createPasswordForm(){
		$oldPasswordInput = $this->createPasswordInput("oldPassword", "Old password");
		$newPasswordInput = $this->createPasswordInput("newPassword", "New password");
		$newPassword2Input = $this->createPasswordInput("newPassword2", "Confirm new password");
		$saveButton = $this->createSavePasswordButton();

		return "<form action='settings' method='POST' enctype='multipart/form-data'>
					<span class='title'>Update password</span>
					<hr class='lineForm'>
					$oldPasswordInput
					$newPasswordInput
					$newPassword2Input
					$saveButton
				</form>";
	}

	private function createFirstNameInput($value){
		if($value == null) $value = "";

		return "<div class='form-group'>
                    <label for='firstName'>First name</label>
                    <input type='text' class='form-control' id='firstName' name='firstName' value='$value' required autocomplete='off'>
                </div>";
	}

	private function createLastNameInput($value){
		if($value == null) $value = "";

		return "<div class='form-group'>
                    <label for='lastName'>Last name</label>
                    <input type='text' class='form-control' id='lastName' name='lastName' value='$value' required autocomplete='off'>
                </div>";
	}

	private function createEmailInput($value){
		if($value == null) $value = "";

		return "<div class='form-group'>
                    <label for='email'>Email</label>
                    <input type='text' class='form-control' id='email' name='email' value='$value' required autocomplete='off'>
                </div>";
	}

	private function createSaveUserDetailsButton(){
		return "<button data-toggle='modal' data-target='#loadingModal' class='btn vizew-btn mt-30' type='submit' name='saveDetailsButton'>Save</button>";
	}

	private function createSavePasswordButton(){
		return "<button data-toggle='modal' data-target='#loadingModal' class='btn vizew-btn mt-30' type='submit' name='savePasswordButton'>Save</button>";
	}

	private function createPasswordInput($name, $placeholder){
		return "<div class='form-group'>
                    <label for='$name'>$placeholder</label>
                    <input type='password' class='form-control' id='firstName' name='$name' required autocomplete='off'>
                </div>";
	}
}

?>