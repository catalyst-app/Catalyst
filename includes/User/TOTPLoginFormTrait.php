<?php

namespace Catalyst\User;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues,StaticHTMLField,TextField};
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

		$tokenField = new TextField();
		$tokenField->setDistinguisher("totp-code");
		$tokenField->setLabel("Authentication Code");
		$tokenField->setRequired(true);
		$tokenField->setPattern('[0-9]*');
		$tokenField->setAutocompleteAttribute(AutocompleteValues::OFF);
		$tokenField->addError(90202, ErrorCodes::ERR_90202);
		$tokenField->setMissingErrorCode(90202);
		$tokenField->addError(90203, ErrorCodes::ERR_90203);
		$tokenField->setInvalidErrorCode(90203);
		$form->addField($tokenField);

		$goBackNotice = new StaticHTMLField();
		$goBackNotice->setHtml('<p class="col s12 no-margin">If you would like to go back and login with a different account, click <a href="'.ROOTDIR.'/Login/">here</a></p>');
		$form->addField($goBackNotice);

		$noCodeMessage = new StaticHTMLField();
		$noCodeMessage->setHtml('<p class="col s12 no-top-margin">If you do not have access to this code, please <a href="'.ROOTDIR.'Help">contact support</a> with your recovery key.</p>');
		$form->addField($noCodeMessage);

		return $form;
	}
}
