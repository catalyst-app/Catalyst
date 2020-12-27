<?php

namespace Catalyst\Character;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\DynamicRedirectCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues,CheckboxField,ColorField,HiddenInputField,MarkdownField,MultipleImageWithNsfwCaptionAndInfoField,StaticHTMLField,TextField};
use \Catalyst\Form\Form;
use \Catalyst\Secrets;

/**
 * edit character form
 */
trait EditCharacterFormTrait {
	/**
	 * Get the form used to edit a character
	 * 
	 * @param Character|null $character Character to prefill values with
	 * @return Form
	 */
	public static function getEditCharacterForm(?Character $character=null) : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/character/edit/");
		$form->setButtonText("SAVE");
		$form->setPrimary(true);

		$completionAction = new DynamicRedirectCompletionAction();
		$form->setCompletionAction($completionAction);

		$tokenField = new HiddenInputField();
		$tokenField->setDistinguisher("token");
		$tokenField->setHiddenInputId("character-token");
		$form->addField($tokenField);

		$nameField = new TextField();
		$nameField->setDistinguisher("name");
		$nameField->setLabel("Name");
		$nameField->setRequired(true);
		$nameField->setPattern('^.{2,255}$');
		$nameField->setMaxLength(255);
		$nameField->setAutocompleteAttribute(AutocompleteValues::NICKNAME);
		$nameField->setCustomErrorMessage("patternMismatch", "Please use at least two characters");
		if (!is_null($character)) {
			$nameField->setPrefilledValue($character->getName());
		}
		$form->addField($nameField);

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
		$descriptionField->setAutocompleteAttribute(AutocompleteValues::OFF);
		if (!is_null($character)) {
			$descriptionField->setPrefilledValue($character->getDescription());
		}
		$form->addField($descriptionField);

		$imagesField = new MultipleImageWithNsfwCaptionAndInfoField();
		$imagesField->setDistinguisher("images");
		$imagesField->setLabel("Images");
		$imagesField->setRequired(false);
		$imagesField->setMaxHumanSize('10MB');
		$imagesField->setInfoLabel('Artist/Source');
		$imagesField->setAutocompleteAttribute(AutocompleteValues::PHOTO);
		$imagesField->addError(91006, ErrorCodes::ERR_91006);
		$imagesField->setMissingErrorCode(91006);
		$imagesField->addError(91007, ErrorCodes::ERR_91007);
		$imagesField->setInvalidErrorCode(91007);
		$imagesField->addError(91005, ErrorCodes::ERR_91005);
		$imagesField->setTooLargeErrorCode(91005);
		$imagesField->setInfoCaptionDelimiter("**Artist:**");
		if (!is_null($character)) {
			$imagesField->setPrefilledValue($character->getImageSet());
		}
		$form->addField($imagesField);

		$colorField = new ColorField();
		$colorField->setDistinguisher("color");
		$colorField->setLabel("Color");
		$colorField->setRequired(true);
		if (!is_null($character)) {
			$colorField->setPrefilledValue($character->getColor());
		}
		$form->addField($colorField);

		$publicCheckboxField = new CheckboxField();
		$publicCheckboxField->setDistinguisher("public");
		$publicCheckboxField->setLabel("Make this character public");
		$publicCheckboxField->setPrefilledValue(true);
		$publicCheckboxField->setRequired(false);
		if (!is_null($character)) {
			$publicCheckboxField->setPrefilledValue($character->isPublic());
		}
		$form->addField($publicCheckboxField);

		$form->addStaticHtml('<p class="col s12 no-margin">If this character is public, anyone can see it on your profile and access it with its link.  Otherwise, only you and artists you commission may see it.</p>');

		return $form;
	}
}
