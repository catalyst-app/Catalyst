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
	public static function getRegisterForm() : Form {
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
		$usernameField->addError(90301, ErrorCodes::ERR_90301);
		$usernameField->setMissingErrorCode(90301);
		$usernameField->addError(90302, ErrorCodes::ERR_90302);
		$usernameField->setInvalidErrorCode(90302);
		$usernameField->addError(90303, ErrorCodes::ERR_90303);
		$form->addField($usernameField);

		$nicknameField = new TextField();
		$nicknameField->setDistinguisher("nickname");
		$nicknameField->setLabel("Nickname");
		$nicknameField->setRequired(false);
		$nicknameField->setMaxLength(100);
		$nicknameField->setPattern('^.{2,100}$');
		$nicknameField->setAutocompleteAttribute(AutocompleteValues::NICKNAME);
		$nicknameField->addError(90304, ErrorCodes::ERR_90304);
		$nicknameField->setMissingErrorCode(90304);
		$nicknameField->addError(90305, ErrorCodes::ERR_90305);
		$nicknameField->setInvalidErrorCode(90305);
		$form->addField($nicknameField);

		$emailField = new EmailField();
		$emailField->setDistinguisher("email");
		$emailField->setLabel("Email");
		$emailField->setRequired(false);
		$emailField->addError(90306, ErrorCodes::ERR_90306);
		$emailField->setMissingErrorCode(90306);
		$emailField->addError(90307, ErrorCodes::ERR_90307);
		$emailField->setInvalidErrorCode(90307);
		$emailField->addError(90308, ErrorCodes::ERR_90308);
		$emailField->addError(90324, ErrorCodes::ERR_90324);
		$form->addField($emailField);

		$passwordField = new PasswordField();
		$passwordField->setDistinguisher("password");
		$passwordField->setLabel("Password");
		$passwordField->setRequired(true);
		$passwordField->setMinLength(8);
		$passwordField->setAutocompleteAttribute(AutocompleteValues::NEW_PASSWORD);
		$passwordField->addError(90309, ErrorCodes::ERR_90309);
		$passwordField->setMissingErrorCode(90309);
		$passwordField->addError(90310, ErrorCodes::ERR_90310);
		$passwordField->setInvalidErrorCode(90310);
		$form->addField($passwordField);

		$passwordMinimumMessage = new StaticHTMLField();
		$passwordMinimumMessage->setHtml('<p class="no-top-margin col s12">Please use at least 8 characters, however, a longer, random generated one is suggested.  You may easily generate one <a target="_blank" tabindex="-1" href="https://passwordsgenerator.net/?length=60&symbols=1&numbers=1&lowercase=1&uppercase=1&similar=0&ambiguous=0&client=1&autoselect=1">here</a>.</p>');
		$form->addField($passwordMinimumMessage);

		$confirmPasswordField = new ConfirmPasswordField();
		$confirmPasswordField->setDistinguisher("confirm-password");
		$confirmPasswordField->setLabel("Confirm Password");
		$confirmPasswordField->setRequired(true);
		$confirmPasswordField->setMinLength(8);
		$confirmPasswordField->setAutocompleteAttribute(AutocompleteValues::NEW_PASSWORD);
		$confirmPasswordField->addError(90311, ErrorCodes::ERR_90311);
		$confirmPasswordField->setMissingErrorCode(90311);
		$confirmPasswordField->addError(90312, ErrorCodes::ERR_90312);
		$confirmPasswordField->setInvalidErrorCode(90312);
		$confirmPasswordField->setLinkedField($passwordField);
		$form->addField($confirmPasswordField);

		$colorField = new ColorField();
		$colorField->setDistinguisher("color");
		$colorField->setLabel("Color");
		$colorField->setRequired(true);
		$colorField->setAutocompleteAttribute(AutocompleteValues::ON);
		$colorField->addError(90313, ErrorCodes::ERR_90313);
		$colorField->setMissingErrorCode(90313);
		$colorField->addError(90314, ErrorCodes::ERR_90314);
		$colorField->setInvalidErrorCode(90314);
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
		$nsfwProfilePictureField->setLabel("My profile picture is explicit or mature");
		$nsfwProfilePictureField->setRequired(false);
		$nsfwProfilePictureField->addError(90318, ErrorCodes::ERR_90318);
		$nsfwProfilePictureField->setMissingErrorCode(90318);
		$nsfwProfilePictureField->addError(90318, ErrorCodes::ERR_90318);
		$nsfwProfilePictureField->setInvalidErrorCode(90318);
		$form->addField($nsfwProfilePictureField);

		$explicitDefinitionMessage = new StaticHTMLField();
		$explicitDefinitionMessage->setHtml('<p class="no-top-margin col s12">Go <a target="_blank" href="'.ROOTDIR.'FAQ/#explicit">here</a> to see the difference between safe, mature, and explicit.</p>');
		$form->addField($explicitDefinitionMessage);

		$nsfwAccessField = new CheckboxField();
		$nsfwAccessField->setDistinguisher("nsfw-access");
		$nsfwAccessField->setLabel("I am above 18 years old and wish to see NSFW content");
		$nsfwAccessField->setRequired(false);
		$nsfwAccessField->addError(90319, ErrorCodes::ERR_90319);
		$nsfwAccessField->setMissingErrorCode(90319);
		$nsfwAccessField->addError(90319, ErrorCodes::ERR_90319);
		$nsfwAccessField->setInvalidErrorCode(90319);
		$form->addField($nsfwAccessField);

		$minimumAgeField = new CheckboxField();
		$minimumAgeField->setDistinguisher("age");
		$minimumAgeField->setLabel("I am above 13 years old, or 16 if I am in the EU");
		$minimumAgeField->setRequired(true);
		$minimumAgeField->addError(90327, ErrorCodes::ERR_90327);
		$minimumAgeField->setMissingErrorCode(90327);
		$minimumAgeField->addError(90328, ErrorCodes::ERR_90328);
		$minimumAgeField->setInvalidErrorCode(90328);
		$form->addField($minimumAgeField);

		$tosAcceptanceField = new CheckboxField();
		$tosAcceptanceField->setDistinguisher("tos-acceptance");
		$tosAcceptanceField->setLabel('I accept the <a target="_blank" href="'.ROOTDIR.'Help/TOS">terms of service</a> and agree to and understand the <a target="_blank" href="'.ROOTDIR.'Help/Privacy">privacy policy</a>');
		$tosAcceptanceField->setRequired(true);
		$tosAcceptanceField->addError(90320, ErrorCodes::ERR_90320);
		$tosAcceptanceField->setMissingErrorCode(90320);
		$tosAcceptanceField->addError(90321, ErrorCodes::ERR_90321);
		$tosAcceptanceField->setInvalidErrorCode(90321);
		$form->addField($tosAcceptanceField);

		$referrerField = new TextField();
		$referrerField->setDistinguisher("referrer");
		$referrerField->setLabel("Referrer (leave blank if none)");
		$referrerField->setRequired(false);
		$referrerField->setMaxLength(64);
		$referrerField->setPattern('^([A-Za-z0-9._-]){2,64}$');
		$referrerField->setHelperText("2-64 characters of letters, numbers, period, dashes, and underscores only.");
		$referrerField->setAutocompleteAttribute(AutocompleteValues::ON);
		$referrerField->addError(90325, ErrorCodes::ERR_90325);
		$referrerField->setInvalidErrorCode(90325);
		$referrerField->addError(90326, ErrorCodes::ERR_90326);
		$form->addField($referrerField);

		$captchaField = new CaptchaField();
		$captchaField->setDistinguisher("captcha");
		$captchaField->setRequired(true);
		$captchaField->setSiteKey("6Lf7A0EUAAAAAM7naF_3NGWGVAxMUK-qPQABEdAl");
		$captchaField->setSecretKey(Secrets::REGISTER_CAPTCHA_SECRET);
		$captchaField->addError(90322, ErrorCodes::ERR_90322);
		$captchaField->setMissingErrorCode(90322);
		$captchaField->addError(90323, ErrorCodes::ERR_90323);
		$captchaField->setInvalidErrorCode(90323);
		$form->addField($captchaField);

		return $form;
	}
}
