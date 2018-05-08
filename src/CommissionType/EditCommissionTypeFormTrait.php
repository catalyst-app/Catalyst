<?php

namespace Catalyst\CommissionType;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues, CheckboxField, HiddenInputField, MarkdownField, MultipleImageWithNsfwCaptionAndInfoField, NumberField, StaticHTMLField, SubformMultipleEntryField, SubformMultipleEntryFieldWithRows, TextField, ToggleableButtonSetField, WrappedField};
use \Catalyst\Form\Form;

/**
 * trait which contains the edit commission type form
 */
trait EditCommissionTypeFormTrait {
	/**
	 * Get the form used to edit commission type
	 *
	 * @param CommissionType|null $commissionType
	 * @return Form
	 */
	public static function getEditCommissionTypeForm(?CommissionType $commissionType=null) : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/commission_type/update/");
		$form->setButtonText("SAVE");
		$form->setPrimary(true);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Artist/CommissionTypes");
		$form->setCompletionAction($completionAction);

		$tokenField = new HiddenInputField();
		$tokenField->setDistinguisher("token");
		$tokenField->setSelector("#commission-type-token");
		$tokenField->addError(91746, ErrorCodes::ERR_91746);
		$tokenField->setMissingErrorCode(91746);
		$tokenField->addError(91747, ErrorCodes::ERR_91747);
		$tokenField->setInvalidErrorCode(91747);
		$form->addField($tokenField);

		$nameField = new TextField();
		$nameField->setDistinguisher("name");
		$nameField->setLabel("Name");
		$nameField->setRequired(true);
		$nameField->setPattern('^.{2,255}$');
		$nameField->setMaxLength(255);
		$nameField->setAutocompleteAttribute(AutocompleteValues::NICKNAME);
		$nameField->addError(91701, ErrorCodes::ERR_91701);
		$nameField->setMissingErrorCode(91701);
		$nameField->addError(91702, ErrorCodes::ERR_91702);
		$nameField->setInvalidErrorCode(91702);
		if (!is_null($commissionType)) {
			$nameField->setPrefilledValue($commissionType->getName());
		}
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
		$blurbField->setAutocompleteAttribute(AutocompleteValues::ON);
		$blurbField->addError(91703, ErrorCodes::ERR_91703);
		$blurbField->setMissingErrorCode(91703);
		$blurbField->addError(91704, ErrorCodes::ERR_91704);
		$blurbField->setInvalidErrorCode(91704);
		$form->addField($blurbField);

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
		$descriptionField->setAutocompleteAttribute(AutocompleteValues::ON);
		$descriptionField->addError(91705, ErrorCodes::ERR_91705);
		$descriptionField->setMissingErrorCode(91705);
		$descriptionField->addError(91706, ErrorCodes::ERR_91706);
		$descriptionField->setInvalidErrorCode(91706);
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
		$baseCostField->setAutocompleteAttribute(AutocompleteValues::ON);
		$baseCostField->addError(91707, ErrorCodes::ERR_91707);
		$baseCostField->setMissingErrorCode(91707);
		$baseCostField->addError(91708, ErrorCodes::ERR_91708);
		$baseCostField->setInvalidErrorCode(91708);

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
		$baseCostUsdField->addError(91709, ErrorCodes::ERR_91709);
		$baseCostUsdField->setMissingErrorCode(91709);
		$baseCostUsdField->addError(91710, ErrorCodes::ERR_91710);
		$baseCostUsdField->setInvalidErrorCode(91710);

		$baseCostUsdFieldWrapper->setField($baseCostUsdField);
		$form->addField($baseCostUsdFieldWrapper);

		$attributesGroupField = new ToggleableButtonSetField();
		$attributesGroupField->setDistinguisher("attributes");
		$attributesGroupField->setLabel("Attributes (used when searching for your page - please select all relevant items)");
		$attributesGroupField->setButtons(CommissionTypeAttribute::getButtonSet());
		$attributesGroupField->addError(91711, ErrorCodes::ERR_91711);
		$attributesGroupField->setMissingErrorCode(91711);
		$attributesGroupField->addError(91712, ErrorCodes::ERR_91712);
		$attributesGroupField->setInvalidErrorCode(91712);
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
		$stageEntryField->setAutocompleteAttribute(AutocompleteValues::ON);
		$stageEntryField->addError(91728, ErrorCodes::ERR_91728);
		$stageEntryField->setMissingErrorCode(91728);
		$stageEntryField->addError(91729, ErrorCodes::ERR_91729);
		$stageEntryField->setInvalidErrorCode(91729);

		$stagesField->addField($stageEntryField);

		$stagesField->addError(91711, ErrorCodes::ERR_91711);
		$stagesField->setMissingErrorCode(91711);
		$stagesField->addError(91712, ErrorCodes::ERR_91712);
		$stagesField->setInvalidErrorCode(91712);

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
		$typeEntryField->setAutocompleteAttribute(AutocompleteValues::ON);
		$typeEntryField->addError(91730, ErrorCodes::ERR_91730);
		$typeEntryField->setMissingErrorCode(91730);
		$typeEntryField->addError(91731, ErrorCodes::ERR_91731);
		$typeEntryField->setInvalidErrorCode(91731);

		$typeEntryWrapper->setField($typeEntryField);
		$paymentsField->addField($typeEntryWrapper);

		$addressEntryWrapper = new WrappedField();
		$addressEntryWrapper->setWrapperClasses("col s12 m7 l8");

		$addressEntryField = new TextField();
		$addressEntryField->setDistinguisher("address-psuedo-field");
		$addressEntryField->setLabel("Address");
		$addressEntryField->setRequired(true);
		$addressEntryField->setAutocompleteAttribute(AutocompleteValues::ON);
		$addressEntryField->addError(91732, ErrorCodes::ERR_91732);
		$addressEntryField->setMissingErrorCode(91732);
		$addressEntryField->addError(91732, ErrorCodes::ERR_91732);
		$addressEntryField->setInvalidErrorCode(91732);

		$addressEntryWrapper->setField($addressEntryField);
		$paymentsField->addField($addressEntryWrapper);

		$instructionsEntryWrapper = new WrappedField();
		$instructionsEntryWrapper->setWrapperClasses("col s12");

		$instructionsField = new TextField();
		$instructionsField->setDistinguisher("instructions-psuedo-field");
		$instructionsField->setLabel("Instructions/Notes");
		$instructionsField->setRequired(false);
		$instructionsField->setAutocompleteAttribute(AutocompleteValues::ON);
		$instructionsField->addError(91733, ErrorCodes::ERR_91733);
		$instructionsField->setMissingErrorCode(91733);
		$instructionsField->addError(91733, ErrorCodes::ERR_91733);
		$instructionsField->setInvalidErrorCode(91733);
		
		$instructionsEntryWrapper->setField($instructionsField);
		$paymentsField->addField($instructionsEntryWrapper);

		$paymentsField->addError(91720, ErrorCodes::ERR_91720);
		$paymentsField->setMissingErrorCode(91720);
		$paymentsField->addError(91721, ErrorCodes::ERR_91721);
		$paymentsField->setInvalidErrorCode(91721);

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
		$modifierEntryField->addError(91734, ErrorCodes::ERR_91734);
		$modifierEntryField->setMissingErrorCode(91734);
		$modifierEntryField->addError(91735, ErrorCodes::ERR_91735);
		$modifierEntryField->setInvalidErrorCode(91735);

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
		$baseCostEntryField->addError(91736, ErrorCodes::ERR_91736);
		$baseCostEntryField->setMissingErrorCode(91736);
		$baseCostEntryField->addError(91737, ErrorCodes::ERR_91737);
		$baseCostEntryField->setInvalidErrorCode(91737);

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
		$baseCostUsdEntryField->addError(91738, ErrorCodes::ERR_91738);
		$baseCostUsdEntryField->setMissingErrorCode(91738);
		$baseCostUsdEntryField->addError(91739, ErrorCodes::ERR_91739);
		$baseCostUsdEntryField->setInvalidErrorCode(91739);
		
		$baseCostUsdEntryWrapper->setField($baseCostUsdEntryField);
		$modifiersField->addField($baseCostUsdEntryWrapper);

		$modifiersField->addError(91718, ErrorCodes::ERR_91718);
		$modifiersField->setMissingErrorCode(91718);
		$modifiersField->addError(91719, ErrorCodes::ERR_91719);
		$modifiersField->setInvalidErrorCode(91719);

		$form->addField($modifiersField);

		$imagesField = new MultipleImageWithNsfwCaptionAndInfoField();
		$imagesField->setDistinguisher("images");
		$imagesField->setLabel("Images");
		$imagesField->setRequired(false);
		$imagesField->setMaxHumanSize('10MB');
		$imagesField->setInfoLabel('Commissioner');
		$imagesField->setAutocompleteAttribute(AutocompleteValues::PHOTO);
		$imagesField->addError(91725, ErrorCodes::ERR_91725);
		$imagesField->setMissingErrorCode(91725);
		$imagesField->addError(91726, ErrorCodes::ERR_91726);
		$imagesField->setInvalidErrorCode(91726);
		$imagesField->addError(91724, ErrorCodes::ERR_91724);
		$imagesField->setTooLargeErrorCode(91724);
		$form->addField($imagesField);

		$visibleCheckbox = new CheckboxField();
		$visibleCheckbox->setRequired(false);
		$visibleCheckbox->setLabel("This commission type should be listed publicly on my page");
		$visibleCheckbox->setDistinguisher("visible");
		$visibleCheckbox->addError(91743, ErrorCodes::ERR_91743);
		$visibleCheckbox->setMissingErrorCode(91743);
		$visibleCheckbox->addError(91744, ErrorCodes::ERR_91744);
		$visibleCheckbox->setInvalidErrorCode(91744);
		$form->addField($visibleCheckbox);

		$acceptingHeader = new StaticHTMLField();
		$acceptingHeader->setHtml('<p class="col s12 no-bottom-margin">For this commission type, I am currently accepting:</p>');
		$form->addField($acceptingHeader);

		$acceptingCheckboxGeneric = new CheckboxField();
		$acceptingCheckboxGeneric->setRequired(false);
		$acceptingCheckboxGeneric->addError(91741, ErrorCodes::ERR_91741);
		$acceptingCheckboxGeneric->setMissingErrorCode(91741);
		$acceptingCheckboxGeneric->addError(91742, ErrorCodes::ERR_91742);
		$acceptingCheckboxGeneric->setInvalidErrorCode(91742);
		foreach (["Quotes", "Requests", "Trades", "Commissions"] as $item) {
			$acceptingCheckboxGeneric->setLabel($item);
			$acceptingCheckboxGeneric->setDistinguisher("accepting-".strtolower($item));
			$form->addField(clone $acceptingCheckboxGeneric);
		}

		return $form;
	}
}
