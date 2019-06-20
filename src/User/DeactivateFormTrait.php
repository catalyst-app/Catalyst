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
		$usernameField->setCustomErrorMessage("patternMismatch", "This is not a valid username");
		$usernameField->setCustomErrorMessage("notCurrentUsername", "This does not match your username");
		$form->addField($usernameField);

		$passwordField = new PasswordField();
		$passwordField->setDistinguisher("password");
		$passwordField->setLabel("Password");
		$passwordField->setRequired(true);
		$passwordField->setAutocompleteAttribute(AutocompleteValues::CURRENT_PASSWORD);
		$form->addField($passwordField);

		return $form;
	}
}
