<?php

namespace Catalyst\CommissionType;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{HiddenInputField,JSConfirmField};
use \Catalyst\Form\Form;
use \Catalyst\Secrets;

/**
 * delete commission type form
 */
trait DeleteCommissionTypeFormTrait {
	/**
	 * Get the form used to delete a commission type
	 * 
	 * @return Form
	 */
	public static function getDeleteCommissionTypeForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/commission_type/delete/");
		$form->setButtonText("DELETE");
		$form->setPrimary(false);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Artist/CommissionTypes");
		$form->setCompletionAction($completionAction);

		$tokenField = new HiddenInputField();
		$tokenField->setDistinguisher("token");
		$tokenField->setHiddenInputId("commission-type-token");
		$tokenField->addError(91746, ErrorCodes::ERR_91746);
		$tokenField->setMissingErrorCode(91746);
		$tokenField->addError(91747, ErrorCodes::ERR_91747);
		$tokenField->setInvalidErrorCode(91747);
		$form->addField($tokenField);

		$confirmField = new JSConfirmField();
		$confirmField->setDistinguisher("confirm");
		$confirmField->setRequired(true);
		$confirmField->setPrompt("Are you sure you want to delete this commission type?");
		$form->addField($confirmField);

		return $form;
	}
}
