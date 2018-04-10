<?php

namespace Catalyst\CommissionType;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{MarkdownField, MultipleImageWithNsfwCaptionAndInfoField, NumberField, StaticHTMLField, SubformMultipleEntryField, SubformMultipleEntryFieldWithRows, TextField, ToggleableButtonSetField, WrappedField};
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
trait NewCommissionTypeFormTrait {
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
		$stagesField->setRequired(false);
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

		$paymentsField = new SubformMultipleEntryField();

		$paymentsField->setDistinguisher("payments");
		$paymentsField->setRequired(false);
		$paymentsField->setLabel("Payment Options");
		$paymentsField->setDisplayHtml('<div class="'.SubformMultipleEntryField::ENTRY_ITEM.'"><div class="raw-markdown">**{type-psuedo-field}** ({address-psuedo-field})'."\n".'{instructions-psuedo-field}</div><i class="material-icons right '.SubformMultipleEntryField::REMOVE_BUTTON_CLASS.'">clear</i></div>');

		$typeEntryWrapper = new WrappedField();
		$typeEntryWrapper->setWrapperClasses("col s12 m5 l4");

		$typeEntryField = new TextField();
		$typeEntryField->setDistinguisher("type-psuedo-field");
		$typeEntryField->setLabel("Payment Type");
		$typeEntryField->setRequired(true);
		$typeEntryField->setPattern('^.{2,64}$');
		$typeEntryField->setMaxLength(64);
		$typeEntryField->addError(91530, ErrorCodes::ERR_91530);
		$typeEntryField->setMissingErrorCode(91530);
		$typeEntryField->addError(91531, ErrorCodes::ERR_91531);
		$typeEntryField->setInvalidErrorCode(91531);

		$typeEntryWrapper->setField($typeEntryField);
		$paymentsField->addField($typeEntryWrapper);

		$addressEntryWrapper = new WrappedField();
		$addressEntryWrapper->setWrapperClasses("col s12 m7 l8");

		$addressEntryField = new TextField();
		$addressEntryField->setDistinguisher("address-psuedo-field");
		$addressEntryField->setLabel("Address");
		$addressEntryField->setRequired(true);
		$addressEntryField->addError(91532, ErrorCodes::ERR_91532);
		$addressEntryField->setMissingErrorCode(91532);
		$addressEntryField->addError(91532, ErrorCodes::ERR_91532);
		$addressEntryField->setInvalidErrorCode(91532);

		$addressEntryWrapper->setField($addressEntryField);
		$paymentsField->addField($addressEntryWrapper);

		$instructionsEntryWrapper = new WrappedField();
		$instructionsEntryWrapper->setWrapperClasses("col s12");

		$instructionsField = new TextField();
		$instructionsField->setDistinguisher("instructions-psuedo-field");
		$instructionsField->setLabel("Instructions/Notes");
		$instructionsField->setRequired(false);
		$instructionsField->addError(91533, ErrorCodes::ERR_91533);
		$instructionsField->setMissingErrorCode(91533);
		$instructionsField->addError(91533, ErrorCodes::ERR_91533);
		$instructionsField->setInvalidErrorCode(91533);
		
		$instructionsEntryWrapper->setField($instructionsField);
		$paymentsField->addField($instructionsEntryWrapper);

		$paymentsField->addError(91520, ErrorCodes::ERR_91520);
		$paymentsField->setMissingErrorCode(91520);
		$paymentsField->addError(91521, ErrorCodes::ERR_91521);
		$paymentsField->setInvalidErrorCode(91521);

		$form->addField($paymentsField);

		$modifiersField = new SubformMultipleEntryFieldWithRows();

		$modifiersField->setDistinguisher("modifiers");
		$modifiersField->setRequired(false);
		$modifiersField->setLabel("Modifiers (these can be added to a commission like pizza toppings to a pizza order)");
		$modifiersField->setDisplayHtml('<a href="#" class="'.SubformMultipleEntryField::ENTRY_ITEM.' btn commission-type-mod"><i class="material-icons right '.SubformMultipleEntryField::REMOVE_BUTTON_CLASS.'">clear</i>{modifier-psuedo-field} (+{base-cost-psuedo-field})</div>');
		$modifiersField->setRightBarContents('<p class="inline-block no-bottom-margin"><label for="{uniq}"><input type="checkbox" class="filled-in" id="{uniq}"><span>Multiple</span></label></p><i class="material-icons '.SubformMultipleEntryFieldWithRows::REMOVE_CONTAINER_BUTTON_CLASS.'">clear</i>');
		$modifiersField->setCustomJsAggregator(function(string $dataArrayName, string $entry) : string {
			return $dataArrayName.'["multiple"] = $('.$entry.').closest(".subform-entry-sub-container").find("input[type=checkbox]").is(":checked");';
		});

		$modifierEntryWrapper = new WrappedField();
		$modifierEntryWrapper->setWrapperClasses("col s12");

		$modifierEntryField = new TextField();
		$modifierEntryField->setDistinguisher("modifier-psuedo-field");
		$modifierEntryField->setLabel("Modifier Name");
		$modifierEntryField->setRequired(true);
		$modifierEntryField->setPattern('^.{2,60}$');
		$modifierEntryField->setMaxLength(60);
		$modifierEntryField->addError(91534, ErrorCodes::ERR_91534);
		$modifierEntryField->setMissingErrorCode(91534);
		$modifierEntryField->addError(91535, ErrorCodes::ERR_91535);
		$modifierEntryField->setInvalidErrorCode(91535);

		$modifierEntryWrapper->setField($modifierEntryField);
		$modifiersField->addField($modifierEntryWrapper);

		$baseCostEntryWrapper = new WrappedField();
		$baseCostEntryWrapper->setWrapperClasses("col s12 m6");

		$baseCostEntryField = new TextField();
		$baseCostEntryField->setDistinguisher("base-cost-psuedo-field");
		$baseCostEntryField->setLabel("Base Cost");
		$baseCostEntryField->setRequired(true);
		$baseCostEntryField->setPattern('^.{2,64}$');
		$baseCostEntryField->setMaxLength(64);
		$baseCostEntryField->addError(91536, ErrorCodes::ERR_91536);
		$baseCostEntryField->setMissingErrorCode(91536);
		$baseCostEntryField->addError(91537, ErrorCodes::ERR_91537);
		$baseCostEntryField->setInvalidErrorCode(91537);

		$baseCostEntryWrapper->setField($baseCostEntryField);
		$modifiersField->addField($baseCostEntryWrapper);

		$baseCostUsdEntryWrapper = new WrappedField();
		$baseCostUsdEntryWrapper->setWrapperClasses("col s12 m6");

		$baseCostUsdEntryField = new NumberField();
		$baseCostUsdEntryField->setDistinguisher("base-cost-usd-psuedo-field");
		$baseCostUsdEntryField->setLabel("Base Cost (USD)");
		$baseCostUsdEntryField->setRequired(true);
		$baseCostUsdEntryField->setPrecision(2);
		$baseCostUsdEntryField->setMin(0);
		$baseCostUsdEntryField->setMax(1000000);
		$baseCostUsdEntryField->addError(91538, ErrorCodes::ERR_91538);
		$baseCostUsdEntryField->setMissingErrorCode(91538);
		$baseCostUsdEntryField->addError(91539, ErrorCodes::ERR_91539);
		$baseCostUsdEntryField->setInvalidErrorCode(91539);
		
		$baseCostUsdEntryWrapper->setField($baseCostUsdEntryField);
		$modifiersField->addField($baseCostUsdEntryWrapper);

		$modifiersField->addError(91518, ErrorCodes::ERR_91518);
		$modifiersField->setMissingErrorCode(91518);
		$modifiersField->addError(91519, ErrorCodes::ERR_91519);
		$modifiersField->setInvalidErrorCode(91519);

		$form->addField($modifiersField);

		$imagesField = new MultipleImageWithNsfwCaptionAndInfoField();
		$imagesField->setDistinguisher("images");
		$imagesField->setLabel("Images");
		$imagesField->setRequired(false);
		$imagesField->setMaxHumanSize('10MB');
		$imagesField->setInfoLabel('Commissioner');
		$imagesField->addError(91525, ErrorCodes::ERR_91525);
		$imagesField->setMissingErrorCode(91525);
		$imagesField->addError(91526, ErrorCodes::ERR_91526);
		$imagesField->setInvalidErrorCode(91526);
		$imagesField->addError(91524, ErrorCodes::ERR_91524);
		$imagesField->setTooLargeErrorCode(91524);
		$form->addField($imagesField);

		return $form;
	}
}
