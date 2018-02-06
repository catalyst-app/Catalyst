<?php

namespace Catalyst\Form;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\{AutoClosingModalCompletionAction,ConcreteRedirectCompletionAction};
use \Catalyst\Form\Field\{CaptchaField,EmailField,PasswordField,StaticHTMLField,TextField};
use \Catalyst\Form\Form;
use \Catalyst\Secrets;
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
		$form->setPrimary(false);

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
		$form->setPrimary(false);

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
	 * Get all Forms functions defined in the repository
	 * 
	 * @return Form[] All forms in the repository
	 */
	public static function getAllForms() : array {
		$reflectedClass = new ReflectionClass(__CLASS__);
		$classMethods = $reflectedClass->getMethods();

		$forms = [];
		foreach ($classMethods as $method) {
			if ($method->getReturnType()->getName() == 'Catalyst\Form\Form') {
				$forms[] = call_user_func([__CLASS__, $method->getName()]);
			}
		}

		return $forms;
	}
}

