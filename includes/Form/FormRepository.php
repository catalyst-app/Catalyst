<?php

namespace Catalyst\Form;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\{
	AutoClosingModalCompletionAction,
	ConcreteRedirectCompletionAction,
	ConditionalCompletionAction
};
use \Catalyst\Form\Field\{
	CaptchaField,
	CheckboxField,
	ColorField,
	ConfirmPasswordField,
	EmailField,
	ImageField,
	PasswordField,
	RawLabelCheckboxField,
	StaticHTMLField,
	TextField};
use \Catalyst\Form\Form;
use \Catalyst\Secrets;
use \Catalyst\Tokens;
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
	 * 
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
	 * 
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
	 * 
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
	 * 
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
	 * 
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
	 * Get all Forms functions defined in the repository
	 * 
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
