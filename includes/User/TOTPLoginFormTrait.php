<?php

namespace Catalyst\User;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{StaticHTMLField,TextField};
use \Catalyst\Form\Form;
use \Catalyst\Secrets;

/**
 * TOTP login form
 */
trait TOTPLoginFormTrait {
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
}
