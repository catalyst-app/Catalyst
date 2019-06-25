<?php

namespace Catalyst\User;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues,TextField,PasswordField,CaptchaField};
use \Catalyst\Form\Form;
use \Catalyst\Secrets;

/**
 * Login form
 */
trait LoginFormTrait {
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
		$usernameField->setAutocompleteAttribute(AutocompleteValues::USERNAME);
		$usernameField->setCustomErrorMessage("usernameDoesNotExist", "This username does not exist");
		$usernameField->setCustomErrorMessage("suspended", "Your account has been suspended.  Check your e-mail or contact support for more information");
		$usernameField->setCustomErrorMessage("deactivated", "This account has been permanently deactivated.");
		$form->addField($usernameField);

		$passwordField = new PasswordField();
		$passwordField->setDistinguisher("password");
		$passwordField->setLabel("Password");
		$passwordField->setRequired(true);
		$passwordField->setAutocompleteAttribute(AutocompleteValues::CURRENT_PASSWORD);
		$form->addField($passwordField);

		$captchaField = new CaptchaField();
		$captchaField->setDistinguisher("captcha");
		$captchaField->setRequired(true);
		$captchaField->setSiteKey("6LfGBUEUAAAAAIC4spvBe8kIKhQlU_JsAVuTfnid");
		$captchaField->setSecretKey(Secrets::LOGIN_CAPTCHA_SECRET);
		$form->addField($captchaField);

		return $form;
	}
}
