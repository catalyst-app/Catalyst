<?php

namespace Catalyst\User;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues,PasswordField,StaticHTMLField,TextField};
use \Catalyst\Form\Form;
use \Catalyst\{Secrets,Tokens};

/**
 * deactivate form
 */
trait DeactivateFormTrait {
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
		$usernameField->setAutocompleteAttribute(AutocompleteValues::USERNAME);
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
		$passwordField->setAutocompleteAttribute(AutocompleteValues::CURRENT_PASSWORD);
		$passwordField->addError(90603, ErrorCodes::ERR_90603);
		$passwordField->setMissingErrorCode(90603);
		$passwordField->addError(90604, ErrorCodes::ERR_90604);
		$passwordField->setInvalidErrorCode(90604);
		$form->addField($passwordField);

		return $form;
	}
}
