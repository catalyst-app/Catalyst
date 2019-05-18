<?php

namespace Catalyst\Character;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{HiddenInputField,JSConfirmField};
use \Catalyst\Form\Form;
use \Catalyst\Secrets;

/**
 * delete character form
 */
trait DeleteCharacterFormTrait {
	/**
	 * Get the form used to delete a character
	 * 
	 * @return Form
	 */
	public static function getDeleteCharacterForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/character/delete/");
		$form->setButtonText("DELETE");
		$form->setPrimary(false);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Character");
		$form->setCompletionAction($completionAction);

		$tokenField = new HiddenInputField();
		$tokenField->setDistinguisher("token");
		$tokenField->setHiddenInputId("character-token");
		$form->addField($tokenField);

		$confirmField = new JSConfirmField();
		$confirmField->setDistinguisher("confirm");
		$confirmField->setRequired(true);
		$confirmField->setPrompt("Are you sure you want to delete this character?");
		$form->addField($confirmField);

		return $form;
	}
}
