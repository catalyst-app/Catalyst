<?php

namespace Catalyst\User;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues, CaptchaField, CheckboxField, ColorField, ConfirmPasswordField, EmailField, ImageField, PasswordField, StaticHTMLField, TextField};
use \Catalyst\Form\Form;
use \Catalyst\Secrets;

/**
 * registration form
 */
trait RegisterFormTrait {
	/**
	 * Register form
	 *
	 * See /Register for form usage
	 * @return Form Form for registering a new user
	 */
	public static function getRegisterForm(): Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/register/");
		$form->setButtonText("REGISTER");
		$form->setPrimary(true);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Dashboard");
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
		$form->addField($usernameField);

		$nicknameField = new TextField();
		$nicknameField->setDistinguisher("nickname");
		$nicknameField->setLabel("Nickname");
		$nicknameField->setRequired(false);
		$nicknameField->setMaxLength(100);
		$nicknameField->setPattern('^.{2,100}$');
		$nicknameField->setAutocompleteAttribute(AutocompleteValues::NICKNAME);
		$nicknameField->setCustomErrorMessage("patternMismatch", "Please use at least two characters");
		$form->addField($nicknameField);

		$emailField = new EmailField();
		$emailField->setDistinguisher("email");
		$emailField->setLabel("Email");
		$emailField->setRequired(false);
		$emailField->setCustomErrorMessage("alreadyInUse", ErrorCodes::ERR_90308);
		$form->addField($emailField);

		$passwordField = new ConfirmPasswordField();
		$passwordField->setDistinguisher("password");
		$passwordField->setLabel("Password");
		$passwordField->setRequired(true);
		$passwordField->setMinLength(8);
		$form->addField($passwordField);

		$form->addStaticHtml('<p class="no-top-margin col s12">Please use at least 8 characters, however, a longer, random generated one is suggested.  You may easily generate one <a target="_blank" tabindex="-1" href="https://passwordsgenerator.net/?length=60&symbols=1&numbers=1&lowercase=1&uppercase=1&similar=0&ambiguous=0&client=1&autoselect=1">here</a>.</p>');

		$colorField = new ColorField();
		$colorField->setDistinguisher("color");
		$colorField->setLabel("Color");
		$colorField->setRequired(true);
		$form->addField($colorField);

		$profilePictureField = new ImageField();
		$profilePictureField->setDistinguisher("profile-picture");
		$profilePictureField->setLabel("Profile Picture");
		$profilePictureField->setRequired(false);
		$profilePictureField->setMaxHumanSize('10MB');
		$profilePictureField->setAutocompleteAttribute(AutocompleteValues::PHOTO);
		// these lost some clarity as what means what due to ImageField
		$profilePictureField->addError(90317, ErrorCodes::ERR_90317);
		$profilePictureField->setMissingErrorCode(90317);
		$profilePictureField->addError(90316, ErrorCodes::ERR_90316);
		$profilePictureField->setInvalidErrorCode(90316);
		$profilePictureField->addError(90315, ErrorCodes::ERR_90315);
		$profilePictureField->setTooLargeErrorCode(90315);
		$form->addField($profilePictureField);

		$nsfwProfilePictureField = new CheckboxField();
		$nsfwProfilePictureField->setDistinguisher("profile-picture-is-nsfw");
		$nsfwProfilePictureField->setLabel("My profile picture is [explicit or mature](" . ROOTDIR . "FAQ/#explicit)");
		$nsfwProfilePictureField->setRequired(false);
		$form->addField($nsfwProfilePictureField);

		$nsfwAccessField = new CheckboxField();
		$nsfwAccessField->setDistinguisher("nsfw-access");
		$nsfwAccessField->setLabel("I am above 18 years old and wish to see NSFW content");
		$nsfwAccessField->setRequired(false);
		$form->addField($nsfwAccessField);

		$minimumAgeField = new CheckboxField();
		$minimumAgeField->setDistinguisher("age");
		$minimumAgeField->setLabel("I am above 13 years old, or 16 if I am in the EU");
		$minimumAgeField->setRequired(true);
		$form->addField($minimumAgeField);

		$tosAcceptanceField = new CheckboxField();
		$tosAcceptanceField->setDistinguisher("tos-acceptance");
		$tosAcceptanceField->setLabel('I accept the [terms of service](' . ROOTDIR . 'Help/TOS) and I agree to and understand the [privacy policy](' . ROOTDIR . 'Help/Privacy)');
		$tosAcceptanceField->setRequired(true);
		$form->addField($tosAcceptanceField);

		$referrerField = new TextField();
		$referrerField->setDistinguisher("referrer");
		$referrerField->setLabel("Referrer (leave blank if none)");
		$referrerField->setRequired(false);
		$referrerField->setMaxLength(64);
		$referrerField->setPattern('^([A-Za-z0-9._-]){2,64}$');
		$referrerField->setHelperText("2-64 characters of letters, numbers, period, dashes, and underscores only.");
		$referrerField->setAutocompleteAttribute(AutocompleteValues::ON);
		$referrerField->setCustomErrorMessage("patternMismatch", "Please make sure you use your referrer's username");
		$referrerField->setCustomErrorMessage("usernameDoesNotExist", "This username does not exist");
		$form->addField($referrerField);

		$captchaField = new CaptchaField();
		$captchaField->setDistinguisher("captcha");
		$captchaField->setRequired(true);
		$captchaField->setSiteKey(Secrets::get("REGISTER_CAPTCHA_SITE"));
		$captchaField->setSecretKey(Secrets::get("REGISTER_CAPTCHA_SECRET"));
		$form->addField($captchaField);

		return $form;
	}
}
