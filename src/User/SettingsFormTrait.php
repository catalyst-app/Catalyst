<?php

namespace Catalyst\User;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\{ConcreteRedirectCompletionAction, ConditionalCompletionAction};
use \Catalyst\Form\Field\{AutocompleteValues, CaptchaField, CheckboxField, ColorField, ConfirmPasswordField, EmailField, ImageField, PasswordField, StaticHTMLField, TextField};
use \Catalyst\Form\Form;
use \Catalyst\Secrets;

/**
 * settings form
 */
trait SettingsFormTrait {
	/**
	 * Settings form
	 * 
	 * See /Settings for form usage
	 * 
	 * @param User $user User to edit settings for
	 * Used to pre-fill field values.  We don't use session directly as there's no reason to interrogate the database for like JS
	 * @return Form Form for editing a user's settings
	 */
	public static function getSettingsForm(?User $user=null) : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/settings/");
		$form->setButtonText("SAVE");
		$form->setPrimary(true);

		$completionAction = new ConditionalCompletionAction();
		$completionAction->addCondition('data.data["redirect_to_totp"] == true', new ConcreteRedirectCompletionAction("Settings/TOTP"));
		$completionAction->setElse(new ConcreteRedirectCompletionAction("Dashboard"));
		$form->setCompletionAction($completionAction);

		$usernameField = new TextField();
		$usernameField->setDistinguisher("username");
		$usernameField->setLabel("Username");
		$usernameField->setRequired(true);
		$usernameField->setMaxLength(64);
		$usernameField->setPattern('^([A-Za-z0-9._-]){2,64}$');
		$usernameField->setHelperText("2-64 characters of letters, numbers, period, dashes, and underscores only.");
		$usernameField->setAutocompleteAttribute(AutocompleteValues::USERNAME);
		$usernameField->setCustomErrorMessage("alreadyInUse", "This username has already been taken, please try something else");
		if (!is_null($user)) {
			$usernameField->setPrefilledValue($user->getUsername());
		}
		$form->addField($usernameField);

		$nicknameField = new TextField();
		$nicknameField->setDistinguisher("nickname");
		$nicknameField->setLabel("Nickname");
		$nicknameField->setRequired(false);
		$nicknameField->setMaxLength(100);
		$nicknameField->setPattern('^.{2,100}$');
		$nicknameField->setAutocompleteAttribute(AutocompleteValues::NICKNAME);
		$nicknameField->setCustomErrorMessage("patternMismatch", "Please use at least two characters");
		if (!is_null($user)) {
			$nicknameField->setPrefilledValue($user->getNickname());
		}
		$form->addField($nicknameField);

		$emailField = new EmailField();
		$emailField->setDistinguisher("email");
		$emailField->setLabel("Email");
		$emailField->setRequired(false);
		$emailField->setAutocompleteAttribute(AutocompleteValues::EMAIL);
		$emailField->setCustomErrorMessage("alreadyInUse", ErrorCodes::ERR_90308);
		if (!is_null($user) && !is_null($user->getEmail())) {
			$emailField->setPrefilledValue($user->getEmail());
		}
		$form->addField($emailField);

		$newPasswordField = new PasswordField();
		$newPasswordField->setDistinguisher("new-password");
		$newPasswordField->setLabel("New Password");
		$newPasswordField->setHelperText('Only use this field if you wish to change your password.');
		$newPasswordField->setRequired(false);
		$newPasswordField->setAutocompleteAttribute(AutocompleteValues::NEW_PASSWORD);
		$form->addField($newPasswordField);

		$confirmNewPasswordField = new ConfirmPasswordField();
		$confirmNewPasswordField->setDistinguisher("confirm-new-password");
		$confirmNewPasswordField->setLabel("Confirm New Password");
		$confirmNewPasswordField->setRequired(false);
		$confirmNewPasswordField->setMinLength(8);
		$confirmNewPasswordField->setAutocompleteAttribute(AutocompleteValues::NEW_PASSWORD);
		$confirmNewPasswordField->addError(90511, ErrorCodes::ERR_90511);
		$confirmNewPasswordField->setMissingErrorCode(90511);
		$confirmNewPasswordField->addError(90512, ErrorCodes::ERR_90512);
		$confirmNewPasswordField->setInvalidErrorCode(90512);
		$confirmNewPasswordField->setLinkedField($newPasswordField);
		$form->addField($confirmNewPasswordField);

		$twoFactorField = new CheckboxField();
		$twoFactorField->setDistinguisher("two-factor");
		$twoFactorField->setLabel("Enable two-factor authentication (Google Authenticator or similar required)");
		$twoFactorField->setRequired(false);
		$twoFactorField->addError(90513, ErrorCodes::ERR_90513);
		$twoFactorField->setMissingErrorCode(90513);
		$twoFactorField->addError(90513, ErrorCodes::ERR_90513);
		$twoFactorField->setInvalidErrorCode(90513);
		if (!is_null($user)) {
			$twoFactorField->setPrefilledValue($user->isTotpEnabled());
		}
		$form->addField($twoFactorField);

		$colorField = new ColorField();
		$colorField->setDistinguisher("color");
		$colorField->setLabel("Color");
		$colorField->setRequired(true);
		$colorField->setAutocompleteAttribute(AutocompleteValues::ON);
		$colorField->addError(90514, ErrorCodes::ERR_90514);
		$colorField->setMissingErrorCode(90514);
		$colorField->addError(90515, ErrorCodes::ERR_90515);
		$colorField->setInvalidErrorCode(90515);
		if (!is_null($user)) {
			$colorField->setPrefilledValue($user->getColor());
		}
		$form->addField($colorField);

		$profilePictureField = new ImageField();
		$profilePictureField->setDistinguisher("profile-picture");
		$profilePictureField->setLabel("Profile Picture");
		$profilePictureField->setRequired(false);
		$profilePictureField->setMaxHumanSize('10MB');
		$profilePictureField->setAutocompleteAttribute(AutocompleteValues::PHOTO);
		// these lost some clarity as what means what due to ImageField
		$profilePictureField->addError(90518, ErrorCodes::ERR_90518);
		$profilePictureField->setMissingErrorCode(90518);
		$profilePictureField->addError(90517, ErrorCodes::ERR_90517);
		$profilePictureField->setInvalidErrorCode(90517);
		$profilePictureField->addError(90516, ErrorCodes::ERR_90516);
		$profilePictureField->setTooLargeErrorCode(90516);
		$form->addField($profilePictureField);

		$nsfwProfilePictureField = new CheckboxField();
		$nsfwProfilePictureField->setDistinguisher("profile-picture-is-nsfw");
		$nsfwProfilePictureField->setLabel("My profile picture is explicit or mature");
		$nsfwProfilePictureField->setRequired(false);
		$nsfwProfilePictureField->addError(90519, ErrorCodes::ERR_90519);
		$nsfwProfilePictureField->setMissingErrorCode(90519);
		$nsfwProfilePictureField->addError(90519, ErrorCodes::ERR_90519);
		$nsfwProfilePictureField->setInvalidErrorCode(90519);
		if (!is_null($user)) {
			$nsfwProfilePictureField->setPrefilledValue($user->getImage()->isNsfw());
		}
		$form->addField($nsfwProfilePictureField);

		$explicitDefinitionMessage = new StaticHTMLField();
		$explicitDefinitionMessage->setHtml('<p class="no-top-margin col s12">Go <a target="_blank" href="'.ROOTDIR.'FAQ/#explicit">here</a> to see the difference between safe, mature, and explicit.</p>');
		$form->addField($explicitDefinitionMessage);

		$nsfwAccessField = new CheckboxField();
		$nsfwAccessField->setDistinguisher("nsfw-access");
		$nsfwAccessField->setLabel("I am above 18 years old and wish to see NSFW content");
		$nsfwAccessField->setRequired(false);
		$nsfwAccessField->addError(90520, ErrorCodes::ERR_90520);
		$nsfwAccessField->setMissingErrorCode(90520);
		$nsfwAccessField->addError(90520, ErrorCodes::ERR_90520);
		$nsfwAccessField->setInvalidErrorCode(90520);
		if (!is_null($user)) {
			$nsfwAccessField->setPrefilledValue($user->isNsfw());
		}
		$form->addField($nsfwAccessField);

		$passwordField = new PasswordField();
		$passwordField->setDistinguisher("password");
		$passwordField->setLabel("Old Password");
		$passwordField->setRequired(true);
		$passwordField->setAutocompleteAttribute(AutocompleteValues::CURRENT_PASSWORD);
		$form->addField($passwordField);

		return $form;
	}
}
