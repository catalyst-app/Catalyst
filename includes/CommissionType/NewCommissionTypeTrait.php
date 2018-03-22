<?php

namespace Catalyst\CommissionType;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{MarkdownField, StaticHTMLField, TextField};
use \Catalyst\Form\Form;

/*
name <255
blurb <255
desc (md)
sort=0
base cost <64
base usd (num, step=2, min=0, max=1000000)
attrs
bool phys addy
bool quotes
bool requests
bool trades
bool commissions
modifier groups creator
	modifiers:
		name <60
		price <64
		usd (0-100000, .01 step)
	sorting
payments
	type < 64
	addy long
	instructions md
	sorting
stages
	label
	sorting
 */

/**
 * trait which contains the new commission type form
 */
trait NewCommissionTypeTrait {
	/**
	 * Get the form used to create a new commission type
	 * 
	 * @return Form
	 */
	public static function getNewCommissionTypeForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/commission_type/new/");
		$form->setButtonText("CREATE");
		$form->setPrimary(true);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Artist/CommissionTypes");
		$form->setCompletionAction($completionAction);

		$nameField = new TextField();
		$nameField->setDistinguisher("name");
		$nameField->setLabel("Name");
		$nameField->setRequired(true);
		$nameField->setPattern('^.{2,255}$');
		$nameField->setMaxLength(255);
		$nameField->addError(91501, ErrorCodes::ERR_91501);
		$nameField->setMissingErrorCode(91501);
		$nameField->addError(91502, ErrorCodes::ERR_91502);
		$nameField->setInvalidErrorCode(91502);
		$form->addField($nameField);

		$blurbField = new TextField();
		$blurbField->setDistinguisher("blurb");
		$blurbField->setLabel("Blurb");
		$blurbField->setRequired(true);
		$blurbField->setPattern('^.{2,255}$');
		$blurbField->setMaxLength(255);
		$blurbField->addError(91503, ErrorCodes::ERR_91503);
		$blurbField->setMissingErrorCode(91503);
		$blurbField->addError(91504, ErrorCodes::ERR_91504);
		$blurbField->setInvalidErrorCode(91504);
		$form->addField($blurbField);

		$blurbNote = new StaticHTMLField();
		$blurbNote->setHtml('<p class="col s12 no-top-margin">This will be shown in search results, wishlists, etc.</p>');
		$form->addField($blurbNote);

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
		$descriptionField->addError(91505, ErrorCodes::ERR_91505);
		$descriptionField->setMissingErrorCode(91505);
		$descriptionField->addError(91506, ErrorCodes::ERR_91506);
		$descriptionField->setInvalidErrorCode(91506);
		$form->addField($descriptionField);

		return $form;
	}
}
