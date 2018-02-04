<?php

namespace Catalyst\Form;

use \Catalyst\API\ErrorCodes;
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
		$form->setCompletionAction(null);
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/email_list/");
		$form->setButtonText("ADD");
		$form->setPrimary(false);

		$contextField = new TextField();
		$contextField->setDistinguisher("context");
		$contextField->setLabel("Name or other information");
		$contextField->setRequired(true);
		$contextField->addError(90003, ErrorCodes::ERR_90001);
		$contextField->setMissingErrorCode(90003);
		$contextField->addError(90004, ErrorCodes::ERR_90001);
		$contextField->setInvalidErrorCode(90004);
		$form->addField($contextField);

		return $form;
	}
}

