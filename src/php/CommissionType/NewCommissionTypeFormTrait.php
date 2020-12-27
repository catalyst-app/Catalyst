<?php

namespace Catalyst\CommissionType;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues, CheckboxField, MarkdownField, MultipleImageWithNsfwCaptionAndInfoField, NumberField, StaticHTMLField, SubformMultipleEntryField, SubformMultipleEntryFieldWithRows, TextField, ToggleableButtonSetField, WrappedField};
use \Catalyst\Form\Form;

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
		$nameField->setAutocompleteAttribute(AutocompleteValues::NICKNAME);
		$nameField->setCustomErrorMessage("patternMismatch", "Please use at least two characters");
		$form->addField($nameField);

		$form->addStaticHtml('<p class="col s12 no-bottom-margin">Blurbs are shown in search results, wishlists, etc.</p>');

		$blurbField = new TextField();
		$blurbField->setDistinguisher("blurb");
		$blurbField->setLabel("Blurb");
		$blurbField->setRequired(true);
		$blurbField->setPattern('^.{2,255}$');
		$blurbField->setMaxLength(255);
		$blurbField->setAutocompleteAttribute(AutocompleteValues::ON);
		$blurbField->setCustomErrorMessage("patternMismatch", "Please use at least two characters");
		$form->addField($blurbField);

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
		$descriptionField->setAutocompleteAttribute(AutocompleteValues::ON);
		$form->addField($descriptionField);

		$form->addStaticHtml('<p class="col s12 no-bottom-margin">The base cost is the minimum cost of the commission type, in whatever units you are charging. </p>');

		$baseCostFieldWrapper = new WrappedField();
		$baseCostFieldWrapper->setWrapperClasses("col s12 m6");

		$baseCostField = new TextField();
		$baseCostField->setDistinguisher("base-cost");
		$baseCostField->setLabel("Base Cost");
		$baseCostField->setRequired(true);
		$baseCostField->setPattern('^.{2,64}$');
		$baseCostField->setMaxLength(64);
		$baseCostField->setAutocompleteAttribute(AutocompleteValues::ON);
		$baseCostField->setCustomErrorMessage("patternMismatch", "Please use at least two characters");

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
		$baseCostUsdField->setAutocompleteAttribute(AutocompleteValues::TRANSACTION_AMOUNT);

		$baseCostUsdFieldWrapper->setField($baseCostUsdField);
		$form->addField($baseCostUsdFieldWrapper);

		$attributesGroupField = new ToggleableButtonSetField();
		$attributesGroupField->setDistinguisher("attributes");
		$attributesGroupField->setLabel("Attributes (used when searching for your page - please select all relevant items)");
		$attributesGroupField->setButtons(CommissionTypeAttribute::getButtonSet());
		$attributesGroupField->addError(91511, ErrorCodes::ERR_91511);
		$attributesGroupField->setMissingErrorCode(91511);
		$attributesGroupField->addError(91512, ErrorCodes::ERR_91512);
		$attributesGroupField->setInvalidErrorCode(91512);
		$form->addField($attributesGroupField);

		$stagesField = new SubformMultipleEntryField();

		$stagesField->setDistinguisher("stages");
		$stagesField->setRequired(false);
		$stagesField->setLabel("Stages (steps of the commission type, can be used to track progress/deadlines)");
		$stagesField->setDisplayHtml('<p class="'.SubformMultipleEntryField::ENTRY_ITEM.'" data-data="">{stage-psuedo-field}<i class="material-icons right '.SubformMultipleEntryField::REMOVE_BUTTON_CLASS.'">clear</i></p>');

		$stageEntryField = new TextField();
		$stageEntryField->setDistinguisher("stage-psuedo-field");
		$stageEntryField->setLabel("Stage");
		$stageEntryField->setRequired(true);
		$stageEntryField->setPattern('^.{2,255}$');
		$stageEntryField->setMaxLength(255);
		$stageEntryField->setAutocompleteAttribute(AutocompleteValues::ON);
		$stageEntryField->setCustomErrorMessage("patternMismatch", "Please use at least two characters");

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
		$paymentsField->setDisplayHtml('<div class="'.SubformMultipleEntryField::ENTRY_ITEM.'" data-data=""><div class="raw-markdown">**{type-psuedo-field}** ({address-psuedo-field})'."\n".'{instructions-psuedo-field}</div><i class="material-icons right '.SubformMultipleEntryField::REMOVE_BUTTON_CLASS.'">clear</i></div>');

		$typeEntryWrapper = new WrappedField();
		$typeEntryWrapper->setWrapperClasses("col s12 m5 l4");

		$typeEntryField = new TextField();
		$typeEntryField->setDistinguisher("type-psuedo-field");
		$typeEntryField->setLabel("Payment Type");
		$typeEntryField->setRequired(true);
		$typeEntryField->setPattern('^.{2,64}$');
		$typeEntryField->setMaxLength(64);
		$typeEntryField->setAutocompleteAttribute(AutocompleteValues::ON);
		$typeEntryField->setCustomErrorMessage("patternMismatch", "Please use at least two characters");

		$typeEntryWrapper->setField($typeEntryField);
		$paymentsField->addField($typeEntryWrapper);

		$addressEntryWrapper = new WrappedField();
		$addressEntryWrapper->setWrapperClasses("col s12 m7 l8");

		$addressEntryField = new TextField();
		$addressEntryField->setDistinguisher("address-psuedo-field");
		$addressEntryField->setLabel("Address");
		$addressEntryField->setRequired(true);
		$addressEntryField->setAutocompleteAttribute(AutocompleteValues::ON);

		$addressEntryWrapper->setField($addressEntryField);
		$paymentsField->addField($addressEntryWrapper);

		$instructionsEntryWrapper = new WrappedField();
		$instructionsEntryWrapper->setWrapperClasses("col s12");

		$instructionsField = new TextField();
		$instructionsField->setDistinguisher("instructions-psuedo-field");
		$instructionsField->setLabel("Instructions/Notes");
		$instructionsField->setRequired(false);
		$instructionsField->setAutocompleteAttribute(AutocompleteValues::ON);
		
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
		$modifiersField->setDisplayHtml('<a href="#" data-data="" class="'.SubformMultipleEntryField::ENTRY_ITEM.' btn commission-type-mod"><i class="material-icons right '.SubformMultipleEntryField::REMOVE_BUTTON_CLASS.'">clear</i>{modifier-psuedo-field} (+{base-cost-psuedo-field})</a>');
		$modifiersField->setRightBarContents('<p class="inline-block no-bottom-margin"><label for="{uniq}"><input type="checkbox" class="filled-in" id="{uniq}"><span>Multiple</span></label></p><i class="material-icons '.SubformMultipleEntryFieldWithRows::REMOVE_CONTAINER_BUTTON_CLASS.'">clear</i>');
		$modifiersField->setCustomJsAggregator(function(string $dataArrayName, string $entry) : string {
			return $dataArrayName.'["multiple"] = $('.$entry.').closest(".subform-entry-sub-container").find("input[type=checkbox]").is(":checked");';
		});
		$modifiersField->setCustomServerSideCheck(function(array &$entry) : void {
			if (!is_bool($entry["multiple"])) {
				$this->throwInvalidError();
			}
		});

		$modifierEntryWrapper = new WrappedField();
		$modifierEntryWrapper->setWrapperClasses("col s12");

		$modifierEntryField = new TextField();
		$modifierEntryField->setDistinguisher("modifier-psuedo-field");
		$modifierEntryField->setLabel("Modifier Name");
		$modifierEntryField->setRequired(true);
		$modifierEntryField->setPattern('^.{2,60}$');
		$modifierEntryField->setMaxLength(60);
		$modifierEntryField->setAutocompleteAttribute(AutocompleteValues::ON);
		$modifierEntryField->setCustomErrorMessage("patternMismatch", "Please use at least two characters");

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
		$baseCostEntryField->setAutocompleteAttribute(AutocompleteValues::ON);
		$baseCostEntryField->setCustomErrorMessage("patternMismatch", "Please use at least two characters");

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
		$baseCostUsdEntryField->setMax(10000);
		$baseCostUsdEntryField->setAutocompleteAttribute(AutocompleteValues::TRANSACTION_AMOUNT);
		
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
		$imagesField->setInfoCaptionDelimiter("**Client:**");
		$imagesField->setAutocompleteAttribute(AutocompleteValues::PHOTO);
		$imagesField->addError(91525, ErrorCodes::ERR_91525);
		$imagesField->setMissingErrorCode(91525);
		$imagesField->addError(91526, ErrorCodes::ERR_91526);
		$imagesField->setInvalidErrorCode(91526);
		$imagesField->addError(91524, ErrorCodes::ERR_91524);
		$imagesField->setTooLargeErrorCode(91524);
		$form->addField($imagesField);

		$visibleCheckbox = new CheckboxField();
		$visibleCheckbox->setRequired(false);
		$visibleCheckbox->setLabel("This commission type should be listed publicly on my page");
		$visibleCheckbox->setDistinguisher("visible");
		$form->addField($visibleCheckbox);

		$form->addStaticHtml('<p class="col s12 no-bottom-margin">For this commission type, I am currently accepting:</p>');

		$acceptingCheckboxGeneric = new CheckboxField();
		$acceptingCheckboxGeneric->setRequired(false);
		foreach (["Quotes", "Requests", "Trades", "Commissions"] as $item) {
			$acceptingCheckboxGeneric->setLabel($item);
			$acceptingCheckboxGeneric->setDistinguisher("accepting-".strtolower($item));
			$form->addField(clone $acceptingCheckboxGeneric);
		}

		return $form;
	}
}
