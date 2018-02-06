<?php

namespace Catalyst\Form;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\{AutoClosingModalCompletionAction,ConcreteRedirectCompletionAction};
use \Catalyst\Form\Field\{CaptchaField,EmailField,TextField};
use \Catalyst\Form\Form;
use \Catalyst\Secrets;
use \ReflectionClass;

/**
 * Simply a repository of forms for the site.  May be split up later, if needed
 */
class FormRepository {
	public static function getDistinguisherFromMethodName(string $in) : string {
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

		$form->setDistinguisher(self::getDistinguisherFromMethodName(__FUNCTION__)); // get-dash-case from camelCase
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

		$form->setDistinguisher(self::getDistinguisherFromMethodName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/login/");
		$form->setButtonText("LOGIN");
		$form->setPrimary(false);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Dashboard");
		$form->setCompletionAction($completionAction);

		$usernameField = new TextField();
		$usernameField->setDistinguisher("username");
		$usernameField->setLabel("Username");
		$usernameField->setRequired(true);
		$usernameField->setPattern('^([A-Za-z0-9._-]){2,64}$');
		$usernameField->addError(90101, ErrorCodes::ERR_90101);
		$usernameField->setMissingErrorCode(90101);
		$usernameField->addError(90102, ErrorCodes::ERR_90102);
		$usernameField->setInvalidErrorCode(90102);
		$form->addField($usernameField);

		$captchaField = new CaptchaField();
		$captchaField->setDistinguisher("captcha");
		$captchaField->setRequired(true);
		$captchaField->setSiteKey("6LfGBUEUAAAAAIC4spvBe8kIKhQlU_JsAVuTfnid");
		$captchaField->setSecretKey(Secrets::LOGIN_CAPTCHA_SECRET);
		$captchaField->addError(90105, ErrorCodes::ERR_90105);
		$captchaField->setMissingErrorCode(90105);
		$captchaField->addError(90106, ErrorCodes::ERR_90106);
		$captchaField->setInvalidErrorCode(90106);
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
			if ($method->getReturnType()->getName() == 'Catalyst\Form\Form') {
				$forms[] = call_user_func([__CLASS__, $method->getName()]);
			}
		}

		return $forms;
	}
}

