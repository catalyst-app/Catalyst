<?php

namespace Catalyst\CommissionType;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{MarkdownField, NumberField, StaticHTMLField, SubformMultipleEntryField, TextField, ToggleableButtonSetField, WrappedField};
use \Catalyst\Form\Form;

/*
name <255
blurb <255
desc (md)
sort=0
base cost <64
base usd (num, step=2, min=0, max=1000000)
attrs
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
bool phys addy
bool quotes
bool requests
bool trades
bool commissions
images
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

		$blurbNote = new StaticHTMLField();
		$blurbNote->setHtml('<p class="col s12 no-bottom-margin">Blurbs are shown in search results, wishlists, etc.</p>');
		$form->addField($blurbNote);

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

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
		$descriptionField->addError(91505, ErrorCodes::ERR_91505);
		$descriptionField->setMissingErrorCode(91505);
		$descriptionField->addError(91506, ErrorCodes::ERR_91506);
		$descriptionField->setInvalidErrorCode(91506);
		$form->addField($descriptionField);

		$costsNote = new StaticHTMLField();
		$costsNote->setHtml('<p class="col s12 no-bottom-margin">The base cost is the minimum cost of the commission type, in whatever units you are charging.  The base cost in USD is not shown to users, but is used for searching and analytics.</p>');
		$form->addField($costsNote);

		$baseCostFieldWrapper = new WrappedField();
		$baseCostFieldWrapper->setWrapperClasses("col s12 m6");

		$baseCostField = new TextField();
		$baseCostField->setDistinguisher("base-cost");
		$baseCostField->setLabel("Base Cost");
		$baseCostField->setRequired(true);
		$baseCostField->setPattern('^.{2,64}$');
		$baseCostField->setMaxLength(64);
		$baseCostField->addError(91507, ErrorCodes::ERR_91507);
		$baseCostField->setMissingErrorCode(91507);
		$baseCostField->addError(91508, ErrorCodes::ERR_91508);
		$baseCostField->setInvalidErrorCode(91508);

		$baseCostFieldWrapper->setField($baseCostField);
		$form->addField($baseCostFieldWrapper);

		$baseCostUsdFieldWrapper = new WrappedField();
		$baseCostUsdFieldWrapper->setWrapperClasses("col s12 m6");

		$baseCostUsdField = new NumberField();
		$baseCostUsdField->setDistinguisher("base-cost-usd");
		$baseCostUsdField->setLabel("Base Cost (USD)");
		$baseCostUsdField->setRequired(true);
		$baseCostUsdField->setPrecision(2);
		$baseCostUsdField->setMin(0);
		$baseCostUsdField->setMax(1000000);
		$baseCostUsdField->addError(91509, ErrorCodes::ERR_91509);
		$baseCostUsdField->setMissingErrorCode(91509);
		$baseCostUsdField->addError(91510, ErrorCodes::ERR_91510);
		$baseCostUsdField->setInvalidErrorCode(91510);

		$baseCostUsdFieldWrapper->setField($baseCostUsdField);
		$form->addField($baseCostUsdFieldWrapper);

		$attributesGroupField = new ToggleableButtonSetField();
		$attributesGroupField->setDistinguisher("attributes");
		$attributesGroupField->setLabel("Attributes (used when searching for your page - please select all relevant items)");
		$attributesGroupField->setButtons(CommissionTypeAttributes::getButtonSet());
		$attributesGroupField->addError(91511, ErrorCodes::ERR_91511);
		$attributesGroupField->setMissingErrorCode(91511);
		$attributesGroupField->addError(91512, ErrorCodes::ERR_91512);
		$attributesGroupField->setInvalidErrorCode(91512);
		$form->addField($attributesGroupField);

		$stagesField = new SubformMultipleEntryField();

		$stagesField->setDistinguisher("stages");
		$stagesField->setLabel("Stages (steps of the commission type, can be used to track progress/deadlines)");
		$stagesField->setDisplayHtml('<p class="'.SubformMultipleEntryField::ENTRY_ITEM.'">{stage-psuedo-field}<i class="material-icons right '.SubformMultipleEntryField::REMOVE_BUTTON_CLASS.'">clear</i></p>');

		$stageEntryField = new TextField();
		$stageEntryField->setDistinguisher("stage-psuedo-field");
		$stageEntryField->setLabel("Stage");
		$stageEntryField->setRequired(true);
		$stageEntryField->setPattern('^.{2,255}$');
		$stageEntryField->setMaxLength(255);

		$stageEntryField->addError(91528, ErrorCodes::ERR_91528);
		$stageEntryField->setMissingErrorCode(91528);
		$stageEntryField->addError(91529, ErrorCodes::ERR_91529);
		$stageEntryField->setInvalidErrorCode(91529);

		$stagesField->addField($stageEntryField);

		$stagesField->addError(91511, ErrorCodes::ERR_91511);
		$stagesField->setMissingErrorCode(91511);
		$stagesField->addError(91512, ErrorCodes::ERR_91512);
		$stagesField->setInvalidErrorCode(91512);

		$form->addField($stagesField);

		return $form;
	}
}
