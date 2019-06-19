<?php

namespace Catalyst\Character;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\DynamicRedirectCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues,CheckboxField,ColorField,HiddenInputField,MarkdownField,MultipleImageWithNsfwCaptionAndInfoField,StaticHTMLField,TextField};
use \Catalyst\Form\Form;
use \Catalyst\User\User;
use \Catalyst\Secrets;

/**
 * new character form
 */
trait NewCharacterFormTrait {
	/**
	 * Get the form used to add a new character
	 * 
	 * See /Character/New
	 * @return Form
	 */
	public static function getNewCharacterForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/character/create/");
		$form->setButtonText("CREATE");
		$form->setPrimary(true);

		$completionAction = new DynamicRedirectCompletionAction();
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

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
		$descriptionField->setAutocompleteAttribute(AutocompleteValues::OFF);
		$descriptionField->addError(90803, ErrorCodes::ERR_90803);
		$descriptionField->setMissingErrorCode(90803);
		$descriptionField->addError(90804, ErrorCodes::ERR_90804);
		$descriptionField->setInvalidErrorCode(90804);
		$form->addField($descriptionField);

		$imagesField = new MultipleImageWithNsfwCaptionAndInfoField();
		$imagesField->setDistinguisher("images");
		$imagesField->setLabel("Images");
		$imagesField->setRequired(false);
		$imagesField->setMaxHumanSize('10MB');
		$imagesField->setInfoLabel('Artist/Source');
		$imagesField->setAutocompleteAttribute(AutocompleteValues::PHOTO);
		$imagesField->addError(90806, ErrorCodes::ERR_90806);
		$imagesField->setMissingErrorCode(90806);
		$imagesField->addError(90807, ErrorCodes::ERR_90807);
		$imagesField->setInvalidErrorCode(90807);
		$imagesField->addError(90805, ErrorCodes::ERR_90805);
		$imagesField->setTooLargeErrorCode(90805);
		$form->addField($imagesField);

		$colorField = new ColorField();
		$colorField->setDistinguisher("color");
		$colorField->setLabel("Color");
		$colorField->setRequired(true);
		if (User::isLoggedIn()) {
			$colorField->setPrefilledValue($_SESSION["user"]->getColor());
		}
		$colorField->setAutocompleteAttribute(AutocompleteValues::ON);
		$colorField->addError(90808, ErrorCodes::ERR_90808);
		$colorField->setMissingErrorCode(90808);
		$colorField->addError(90809, ErrorCodes::ERR_90809);
		$colorField->setInvalidErrorCode(90809);
		$form->addField($colorField);

		$publicCheckboxField = new CheckboxField();
		$publicCheckboxField->setDistinguisher("public");
		$publicCheckboxField->setLabel("Make this character public");
		$publicCheckboxField->setRequired(false);
		$publicCheckboxField->addError(90810, ErrorCodes::ERR_90810);
		$publicCheckboxField->setMissingErrorCode(90810);
		$publicCheckboxField->addError(90811, ErrorCodes::ERR_90811);
		$publicCheckboxField->setInvalidErrorCode(90811);
		$form->addField($publicCheckboxField);

		$publicNotice = new StaticHTMLField();
		$publicNotice->setHtml('<p class="col s12 no-margin">If this character is public, anyone can see it on your profile and access it with its link.  Otherwise, only you and artists you commission may see it.</p>');
		$form->addField($publicNotice);

		return $form;
	}
}
