<?php

namespace Catalyst\Form;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\{
	AutoClosingModalCompletionAction,
	CallUserFuncCompletionAction,
	ConcreteRedirectCompletionAction,
	ConditionalCompletionAction,
	DynamicRedirectCompletionAction
};
use \Catalyst\Form\Field\{
	CaptchaField,
	CheckboxField,
	ColorField,
	ConfirmPasswordField,
	EmailField,
	HiddenInputField,
	ImageField,
	MarkdownField,
	MultipleImageField,
	MultipleImageFieldWithNsfwCaptionAndInfo,
	PasswordField,
	RawLabelCheckboxField,
	SelectField,
	StaticHTMLField,
	TextField};
use \Catalyst\Form\Form;
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Secrets;
use \Catalyst\Tokens;
use \Catalyst\User\User;
use \ReflectionClass;

/**
 * Simply a repository of forms for the site.  May be split up later, if needed
 */
class FormRepository {
	/**
	 * Get a distinguisher from __FUNCTION__ (convert to dash case and remove get)
	 * 
	 * I like this method as it ensures unique names (you can't redefine a function!)
	 * (ok lets be honest this is php you can fucking redefine constants but whatever)
	 * 
	 * @param string $in Function name to get a distinguisher from
	 * @return string Dash-case formatted distinguisher
	 */
	public static function getDistinguisherFromFunctionName(string $in) : string {
		// remove get
		$withoutGet = preg_replace('/^get/', '', $in);
		// convert to dash-case
		$toDashCase = preg_replace('/([a-z])([A-Z])/', '\1-\2', $withoutGet);
		// force lowercase
		return strtolower($toDashCase);
	}

	/**
	 * Get the form used to add a user to the mailing list.
	 * 
	 * See /About for form usage
	 * @return Form Form for adding a user to the mailing list
	 */
	public static function getEmailListAdditionForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/email_list/");
		$form->setButtonText("ADD");
		$form->setPrimary(false);

		$completionAction = new AutoClosingModalCompletionAction();
		$completionAction->setContents("You have been added to the email list!");
		$completionAction->setDelay(10);
		$form->setCompletionAction($completionAction);

		$emailField = new EmailField();
		$emailField->setDistinguisher("email");
		$emailField->setLabel("Email");
		$emailField->setRequired(true);
		$emailField->addError(90001, ErrorCodes::ERR_90001);
		$emailField->setMissingErrorCode(90001);
		$emailField->addError(90002, ErrorCodes::ERR_90002);
		$emailField->setInvalidErrorCode(90002);
		$form->addField($emailField);

		$contextField = new TextField();
		$contextField->setDistinguisher("context");
		$contextField->setLabel("Name or other information");
		$contextField->setRequired(true);
		$contextField->addError(90003, ErrorCodes::ERR_90003);
		$contextField->setMissingErrorCode(90003);
		$contextField->addError(90004, ErrorCodes::ERR_90004);
		$contextField->setInvalidErrorCode(90004);
		$form->addField($contextField);

		return $form;
	}

	/**
	 * Login form
	 * 
	 * See /Login for form usage
	 * @return Form Form for attempting a login
	 */
	public static function getLoginForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/login/");
		$form->setButtonText("LOGIN");
		$form->setPrimary(true);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Dashboard");
		$form->setCompletionAction($completionAction);

		$totpAction = new ConcreteRedirectCompletionAction();
		$totpAction->setRedirectUrl("Login/TOTP");
		$form->addAdditionalCases(90110, $totpAction);

		$usernameField = new TextField();
		$usernameField->setDistinguisher("username");
		$usernameField->setLabel("Username");
		$usernameField->setRequired(true);
		$usernameField->setPattern('^([A-Za-z0-9._-]){2,64}$');
		$usernameField->addError(90101, ErrorCodes::ERR_90101);
		$usernameField->setMissingErrorCode(90101);
		$usernameField->addError(90102, ErrorCodes::ERR_90102);
		$usernameField->setInvalidErrorCode(90102);
		$usernameField->addError(90103, ErrorCodes::ERR_90103);
		$usernameField->addError(90108, ErrorCodes::ERR_90108);
		$usernameField->addError(90109, ErrorCodes::ERR_90109);
		$form->addField($usernameField);

		$passwordField = new PasswordField();
		$passwordField->setDistinguisher("password");
		$passwordField->setLabel("Password");
		$passwordField->setRequired(true);
		$passwordField->setMinLength(8);
		$passwordField->addError(90104, ErrorCodes::ERR_90104);
		$passwordField->setMissingErrorCode(90104);
		$passwordField->addError(90105, ErrorCodes::ERR_90105);
		$passwordField->setInvalidErrorCode(90105);
		$form->addField($passwordField);

		$captchaField = new CaptchaField();
		$captchaField->setDistinguisher("captcha");
		$captchaField->setRequired(true);
		$captchaField->setSiteKey("6LfGBUEUAAAAAIC4spvBe8kIKhQlU_JsAVuTfnid");
		$captchaField->setSecretKey(Secrets::LOGIN_CAPTCHA_SECRET);
		$captchaField->addError(90106, ErrorCodes::ERR_90106);
		$captchaField->setMissingErrorCode(90106);
		$captchaField->addError(90107, ErrorCodes::ERR_90107);
		$captchaField->setInvalidErrorCode(90107);
		$form->addField($captchaField);

		return $form;
	}

	/**
	 * TOTP Login form
	 * 
	 * See /Login/TOTP for form usage
	 * @return Form Form for attempting a TOTP login
	 */
	public static function getTotpLoginForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/totp_login/");
		$form->setButtonText("LOGIN");
		$form->setPrimary(true);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Dashboard");
		$form->setCompletionAction($completionAction);

		$usernameField = new TextField();
		$usernameField->setDistinguisher("totp-code");
		$usernameField->setLabel("Authentication Code");
		$usernameField->setRequired(true);
		$usernameField->setPattern('^[0-9]{6}$');
		$usernameField->addError(90202, ErrorCodes::ERR_90202);
		$usernameField->setMissingErrorCode(90202);
		$usernameField->addError(90203, ErrorCodes::ERR_90203);
		$usernameField->setInvalidErrorCode(90203);
		$form->addField($usernameField);

		$goBackNotice = new StaticHTMLField();
		$goBackNotice->setHtml('<p class="col s12 no-margin">If you would like to go back and login with a different account, click <a href="'.ROOTDIR.'/Login/">here</a></p>');
		$form->addField($goBackNotice);

		$noCodeMessage = new StaticHTMLField();
		$noCodeMessage->setHtml('<p class="col s12 no-top-margin">If you do not have access to this code, please <a href="'.ROOTDIR.'Help">contact support</a> with your recovery key.</p>');
		$form->addField($noCodeMessage);

		return $form;
	}

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
		$usernameField->addError(90301, ErrorCodes::ERR_90301);
		$usernameField->setMissingErrorCode(90301);
		$usernameField->addError(90302, ErrorCodes::ERR_90302);
		$usernameField->setInvalidErrorCode(90302);
		$usernameField->addError(90303, ErrorCodes::ERR_90303);
		$form->addField($usernameField);

		$usernameAcceptedCharactersMessage = new StaticHTMLField();
		$usernameAcceptedCharactersMessage->setHtml('<p class="no-top-margin col s12">2-64 characters of letters, numbers, period, dashes, and underscores only.</p>');
		$form->addField($usernameAcceptedCharactersMessage);

		$nicknameField = new TextField();
		$nicknameField->setDistinguisher("nickname");
		$nicknameField->setLabel("Nickname");
		$nicknameField->setRequired(false);
		$nicknameField->setMaxLength(100);
		$nicknameField->setPattern('^.{2,100}$');
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
		$explicitDefinitionMessage->setHtml('<p class="no-top-margin col s12">Go <a href="'.ROOTDIR.'FAQ/#explicit">here</a> to see the difference between safe, mature, and explicit.</p>');
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

		$tosAcceptanceField = new RawLabelCheckboxField();
		$tosAcceptanceField->setDistinguisher("tos-acceptance");
		$tosAcceptanceField->setLabel('I accept the <a target="_blank" href="'.ROOTDIR.'TOS">terms of service</a>');
		$tosAcceptanceField->setRequired(false);
		$tosAcceptanceField->addError(90320, ErrorCodes::ERR_90320);
		$tosAcceptanceField->setMissingErrorCode(90320);
		$tosAcceptanceField->addError(90321, ErrorCodes::ERR_90321);
		$tosAcceptanceField->setInvalidErrorCode(90321);
		$form->addField($tosAcceptanceField);

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

	/**
	 * Verifies an email address token
	 * 
	 * See /EmailVerification
	 * @return Form Form for verifying a new email
	 */
	public static function getEmailVerificationForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/email_verification/");
		$form->setButtonText("VERIFY");
		$form->setPrimary(true);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Dashboard");
		$form->setCompletionAction($completionAction);

		$tokenField = new TextField();
		$tokenField->setDistinguisher("token");
		$tokenField->setLabel("Token");
		$tokenField->setRequired(true);
		if (array_key_exists("email_token", $_SESSION) && 
			preg_match('/'.Tokens::EMAIL_VERIFICATION_TOKEN_REGEX.'/', $_SESSION["email_token"])) {
			$tokenField->setPrefilledValue($_SESSION["email_token"]);
		}
		$tokenField->setMaxLength(Tokens::EMAIL_VERIFICATION_TOKEN_LENGTH);
		$tokenField->setPattern(Tokens::EMAIL_VERIFICATION_TOKEN_REGEX);
		$tokenField->addError(90401, ErrorCodes::ERR_90401);
		$tokenField->setMissingErrorCode(90401);
		$tokenField->addError(90402, ErrorCodes::ERR_90402);
		$tokenField->setInvalidErrorCode(90402);
		$form->addField($tokenField);

		$captchaField = new CaptchaField();
		$captchaField->setDistinguisher("captcha");
		$captchaField->setRequired(true);
		$captchaField->setSiteKey("6LdGBEEUAAAAAMHsFHz4BRvEnIq1NMuuU_Keo7nn");
		$captchaField->setSecretKey(Secrets::EMAIL_VERIFICATION_CAPTCHA_SECRET);
		$captchaField->addError(90403, ErrorCodes::ERR_90403);
		$captchaField->setMissingErrorCode(90403);
		$captchaField->addError(90404, ErrorCodes::ERR_90404);
		$captchaField->setInvalidErrorCode(90404);
		$form->addField($captchaField);

		return $form;
	}

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
		$usernameField->addError(90501, ErrorCodes::ERR_90501);
		$usernameField->setMissingErrorCode(90501);
		$usernameField->addError(90502, ErrorCodes::ERR_90502);
		$usernameField->setInvalidErrorCode(90502);
		$usernameField->addError(90503, ErrorCodes::ERR_90503);
		if (!is_null($user)) {
			$usernameField->setPrefilledValue($user->getUsername());
		}
		$form->addField($usernameField);

		$usernameAcceptedCharactersMessage = new StaticHTMLField();
		$usernameAcceptedCharactersMessage->setHtml('<p class="no-top-margin col s12">2-64 characters of letters, numbers, period, dashes, and underscores only.</p>');
		$form->addField($usernameAcceptedCharactersMessage);

		$nicknameField = new TextField();
		$nicknameField->setDistinguisher("nickname");
		$nicknameField->setLabel("Nickname");
		$nicknameField->setRequired(false);
		$nicknameField->setMaxLength(100);
		$nicknameField->setPattern('^.{2,100}$');
		$nicknameField->addError(90504, ErrorCodes::ERR_90504);
		$nicknameField->setMissingErrorCode(90504);
		$nicknameField->addError(90505, ErrorCodes::ERR_90505);
		$nicknameField->setInvalidErrorCode(90505);
		if (!is_null($user)) {
			$nicknameField->setPrefilledValue($user->getNickname());
		}
		$form->addField($nicknameField);

		$emailField = new EmailField();
		$emailField->setDistinguisher("email");
		$emailField->setLabel("Email");
		$emailField->setRequired(false);
		$emailField->addError(90506, ErrorCodes::ERR_90506);
		$emailField->setMissingErrorCode(90506);
		$emailField->addError(90507, ErrorCodes::ERR_90507);
		$emailField->setInvalidErrorCode(90507);
		$emailField->addError(90508, ErrorCodes::ERR_90508);
		$emailField->addError(90523, ErrorCodes::ERR_90523);
		if (!is_null($user) && !is_null($user->getEmail())) {
			$emailField->setPrefilledValue($user->getEmail());
		}
		$form->addField($emailField);

		$passwordField = new PasswordField();
		$passwordField->setDistinguisher("new-password");
		$passwordField->setLabel("New Password");
		$passwordField->setRequired(false);
		$passwordField->setMinLength(8);
		$passwordField->addError(90509, ErrorCodes::ERR_90509);
		$passwordField->setMissingErrorCode(90509);
		$passwordField->addError(90510, ErrorCodes::ERR_90510);
		$passwordField->setInvalidErrorCode(90510);
		$form->addField($passwordField);

		$passwordMinimumMessage = new StaticHTMLField();
		$passwordMinimumMessage->setHtml('<p class="no-top-margin col s12">Only use this field if you wish to change your password.</p>');
		$form->addField($passwordMinimumMessage);

		$confirmPasswordField = new ConfirmPasswordField();
		$confirmPasswordField->setDistinguisher("confirm-new-password");
		$confirmPasswordField->setLabel("Confirm New Password");
		$confirmPasswordField->setRequired(false);
		$confirmPasswordField->setMinLength(8);
		$confirmPasswordField->addError(90511, ErrorCodes::ERR_90511);
		$confirmPasswordField->setMissingErrorCode(90511);
		$confirmPasswordField->addError(90512, ErrorCodes::ERR_90512);
		$confirmPasswordField->setInvalidErrorCode(90512);
		$confirmPasswordField->setLinkedField($passwordField);
		$form->addField($confirmPasswordField);

		$twoFactorField = new CheckboxField();
		$twoFactorField->setDistinguisher("two-factor");
		$twoFactorField->setLabel("Enable two-factor authentication (Google Authenticator or similar required)");
		$twoFactorField->setRequired(false);
		$twoFactorField->addError(90513, ErrorCodes::ERR_90513);
		$twoFactorField->setMissingErrorCode(90513);
		$twoFactorField->addError(90513, ErrorCodes::ERR_90513);
		$twoFactorField->setInvalidErrorCode(90513);
		if (!is_null($user) && !is_null($user->getEmail())) {
			$twoFactorField->setPrefilledValue($user->isTotpEnabled());
		}
		$form->addField($twoFactorField);

		$colorField = new ColorField();
		$colorField->setDistinguisher("color");
		$colorField->setLabel("Color");
		$colorField->setRequired(true);
		$colorField->addError(90514, ErrorCodes::ERR_90514);
		$colorField->setMissingErrorCode(90514);
		$colorField->addError(90515, ErrorCodes::ERR_90515);
		$colorField->setInvalidErrorCode(90515);
		if (!is_null($user) && !is_null($user->getEmail())) {
			$colorField->setPrefilledValue($user->getColor());
		}
		$form->addField($colorField);

		$profilePictureField = new ImageField();
		$profilePictureField->setDistinguisher("profile-picture");
		$profilePictureField->setLabel("Profile Picture");
		$profilePictureField->setRequired(false);
		$profilePictureField->setMaxHumanSize('10MB');
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
			$nsfwProfilePictureField->setPrefilledValue($user->isProfilePictureNsfw());
		}
		$form->addField($nsfwProfilePictureField);

		$explicitDefinitionMessage = new StaticHTMLField();
		$explicitDefinitionMessage->setHtml('<p class="no-top-margin col s12">Go <a href="'.ROOTDIR.'FAQ/#explicit">here</a> to see the difference between safe, mature, and explicit.</p>');
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
		$passwordField->setMinLength(8);
		$passwordField->addError(90521, ErrorCodes::ERR_90521);
		$passwordField->setMissingErrorCode(90521);
		$passwordField->addError(90522, ErrorCodes::ERR_90522);
		$passwordField->setInvalidErrorCode(90522);
		$form->addField($passwordField);

		return $form;
	}

	/**
	 * Deactivation form
	 * 
	 * See /Settings for form usage (hidden in a modal)
	 * @return Form Form for deactivating a user
	 */
	public static function getDeactivateForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/deactivate/");
		$form->setButtonText("CONFIRM");
		$form->setPrimary(true);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Login");
		$form->setCompletionAction($completionAction);

		$usernameField = new TextField();
		$usernameField->setDistinguisher("username");
		$usernameField->setLabel("Username");
		$usernameField->setRequired(true);
		$usernameField->setPattern('^([A-Za-z0-9._-]){2,64}$');
		$usernameField->addError(90601, ErrorCodes::ERR_90601);
		$usernameField->setMissingErrorCode(90601);
		$usernameField->addError(90602, ErrorCodes::ERR_90602);
		$usernameField->setInvalidErrorCode(90602);
		$form->addField($usernameField);

		$passwordField = new PasswordField();
		$passwordField->setDistinguisher("password");
		$passwordField->setLabel("Password");
		$passwordField->setRequired(true);
		$passwordField->setMinLength(8);
		$passwordField->addError(90603, ErrorCodes::ERR_90603);
		$passwordField->setMissingErrorCode(90603);
		$passwordField->addError(90604, ErrorCodes::ERR_90604);
		$passwordField->setInvalidErrorCode(90604);
		$form->addField($passwordField);

		return $form;
	}

	/**
	 * Adding a social media network (link) form
	 * 
	 * See /Dashboard and other similar for fomr usage
	 * @return Form Form for adding a social network via. a link
	 */
	public static function getAddNetworkLinkForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/social_media/add_link/");
		$form->setButtonText("ADD");
		$form->setPrimary(false);

		$completionAction = new CallUserFuncCompletionAction();
		$completionAction->setFunc("addSocialMediaChip");
		$form->setCompletionAction($completionAction);

		$destField = new HiddenInputField();
		$destField->setDistinguisher("dest");
		$destField->setSelector("#social-dest-type");
		$form->addField($destField);

		$labelField = new TextField();
		$labelField->setDistinguisher("label");
		$labelField->setLabel("Label");
		$labelField->setRequired(true);
		$labelField->setPattern('^.{2,64}$');
		$labelField->addError(90702, ErrorCodes::ERR_90702);
		$labelField->setMissingErrorCode(90702);
		$labelField->addError(90703, ErrorCodes::ERR_90703);
		$labelField->setInvalidErrorCode(90703);
		$form->addField($labelField);

		$urlField = new TextField();
		$urlField->setDistinguisher("url");
		$urlField->setLabel("URL or email");
		$urlField->setRequired(true);
		$urlField->setPattern('^(https?://.{2,}\..{2,}|.{2,}@.{2,}\..{2,})$');
		$urlField->addError(90704, ErrorCodes::ERR_90704);
		$urlField->setMissingErrorCode(90704);
		$urlField->addError(90705, ErrorCodes::ERR_90705);
		$urlField->setInvalidErrorCode(90705);
		$urlField->addError(90706, ErrorCodes::ERR_90706);
		$urlField->addError(90707, ErrorCodes::ERR_90707);
		$urlField->addError(90708, ErrorCodes::ERR_90708);
		$urlField->addError(90709, ErrorCodes::ERR_90709);
		$urlField->addError(90710, ErrorCodes::ERR_90710);
		$form->addField($urlField);

		return $form;
	}

	/**
	 * Adding a social media network (link) form
	 * 
	 * See /Dashboard and other similar for fomr usage
	 * @return Form Form for adding a social network via. a link
	 */
	public static function getAddNetworkOtherForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/social_media/add_other/");
		$form->setButtonText("ADD");
		$form->setPrimary(false);

		$completionAction = new CallUserFuncCompletionAction();
		$completionAction->setFunc("addSocialMediaChip");
		$form->setCompletionAction($completionAction);

		$destField = new HiddenInputField();
		$destField->setDistinguisher("dest");
		$destField->setSelector("#social-dest-type");
		$form->addField($destField);

		$typeField = new SelectField();
		$typeField->setDistinguisher("type");
		$typeField->setLabel("Social Network");
		$typeField->setOptions(SocialMedia::getOtherNetworkAddSelectArray());
		$typeField->setRequired(true);
		$typeField->addError(90712, ErrorCodes::ERR_90712);
		$typeField->setMissingErrorCode(90712);
		$typeField->addError(90713, ErrorCodes::ERR_90713);
		$typeField->setInvalidErrorCode(90713);
		$form->addField($typeField);

		$labelField = new TextField();
		$labelField->setDistinguisher("label");
		$labelField->setLabel("Label");
		$labelField->setRequired(true);
		$labelField->setPattern('^.{2,64}$');
		$labelField->addError(90702, ErrorCodes::ERR_90702);
		$labelField->setMissingErrorCode(90702);
		$labelField->addError(90703, ErrorCodes::ERR_90703);
		$labelField->setInvalidErrorCode(90703);
		$form->addField($labelField);

		return $form;
	}

	/**
	 * Get the form used to add a new character
	 * 
	 * See /Character/New
	 * @return Form
	 */
	public static function getNewCharacterForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/character/create/");
		$form->setButtonText("CREATE");
		$form->setPrimary(false);

		$completionAction = new DynamicRedirectCompletionAction();
		$form->setCompletionAction($completionAction);

		$nameField = new TextField();
		$nameField->setDistinguisher("name");
		$nameField->setLabel("Name");
		$nameField->setRequired(true);
		$nameField->setPattern('^.{2,255}$');
		$nameField->setMaxLength(255);
		$nameField->addError(90801, ErrorCodes::ERR_90801);
		$nameField->setMissingErrorCode(90801);
		$nameField->addError(90802, ErrorCodes::ERR_90802);
		$nameField->setInvalidErrorCode(90802);
		$form->addField($nameField);

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
		$descriptionField->addError(90803, ErrorCodes::ERR_90803);
		$descriptionField->setMissingErrorCode(90803);
		$descriptionField->addError(90804, ErrorCodes::ERR_90804);
		$descriptionField->setInvalidErrorCode(90804);
		$form->addField($descriptionField);

		$imagesField = new MultipleImageFieldWithNsfwCaptionAndInfo();
		$imagesField->setDistinguisher("images");
		$imagesField->setLabel("Images");
		$imagesField->setRequired(false);
		$imagesField->setMaxHumanSize('10MB');
		$imagesField->addError(90805, ErrorCodes::ERR_90805);
		$imagesField->setMissingErrorCode(90805);
		$imagesField->addError(90806, ErrorCodes::ERR_90806);
		$imagesField->setInvalidErrorCode(90806);
		$imagesField->addError(90807, ErrorCodes::ERR_90807);
		$imagesField->setTooLargeErrorCode(90807);
		$form->addField($imagesField);

		$colorField = new ColorField();
		$colorField->setDistinguisher("color");
		$colorField->setLabel("Color");
		$colorField->setRequired(true);
		if (User::isLoggedIn()) {
			$colorField->setPrefilledValue($_SESSION["user"]->getColor());
		}
		$colorField->addError(90808, ErrorCodes::ERR_90808);
		$colorField->setMissingErrorCode(90808);
		$colorField->addError(90809, ErrorCodes::ERR_90809);
		$colorField->setInvalidErrorCode(90809);
		$form->addField($colorField);

		$publicCheckboxField = new CheckboxField();
		$publicCheckboxField->setDistinguisher("public");
		$publicCheckboxField->setLabel("Make this character public");
		$publicCheckboxField->setRequired(false);
		$publicCheckboxField->addError(90810, ErrorCodes::ERR_90810);
		$publicCheckboxField->setMissingErrorCode(90810);
		$publicCheckboxField->addError(90811, ErrorCodes::ERR_90811);
		$publicCheckboxField->setInvalidErrorCode(90811);
		$form->addField($publicCheckboxField);

		$publicNotice = new StaticHTMLField();
		$publicNotice->setHtml('<p class="col s12 no-margin">If this character is public, anyone can see it on your profile and access it with its link.  Otherwise, only you and artists you commission may see it.</p>');
		$form->addField($publicNotice);

		return $form;
	}

	/**
	 * Get all Forms functions defined in the repository
	 * @return Form[] All forms in the repository
	 */
	public static function getAllForms() : array {
		$reflectedClass = new ReflectionClass(__CLASS__);
		$classMethods = $reflectedClass->getMethods();

		$forms = [];
		foreach ($classMethods as $method) {
			if ($method->getReturnType()->getName() == Form::class) {
				$forms[] = call_user_func([__CLASS__, $method->getName()]);
			}
		}

		return $forms;
	}
}
