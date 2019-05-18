<?php

namespace Catalyst\Integrations;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\CallUserFuncCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues,HiddenInputField,SelectField,TextField};
use \Catalyst\Form\Form;

/**
 * Forms to add social networks to a model which supports them
 */
trait SocialMediaAdditionFormsTrait {
	/**
	 * Adding a social media network (link) form
	 * 
	 * See /Dashboard and other similar for fomr usage
	 * @return Form Form for adding a social network via. a link
	 */
	public static function getAddNetworkLinkForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/social_media/add_link/");
		$form->setButtonText("ADD");
		$form->setPrimary(false);

		$completionAction = new CallUserFuncCompletionAction();
		$completionAction->setFunc("addSocialMediaChip");
		$form->setCompletionAction($completionAction);

		$destField = new HiddenInputField();
		$destField->setDistinguisher("dest");
		$destField->setHiddenInputId("social-dest-type");
		$form->addField($destField);

		$labelField = new TextField();
		$labelField->setDistinguisher("label");
		$labelField->setLabel("Label");
		$labelField->setRequired(true);
		$labelField->setPattern('^.{2,64}$');
		$labelField->setAutocompleteAttribute(AutocompleteValues::ON);
		$labelField->addError(90702, ErrorCodes::ERR_90702);
		$labelField->setMissingErrorCode(90702);
		$labelField->addError(90703, ErrorCodes::ERR_90703);
		$labelField->setInvalidErrorCode(90703);
		$form->addField($labelField);

		$urlField = new TextField();
		$urlField->setDistinguisher("url");
		$urlField->setLabel("URL or email");
		$urlField->setRequired(true);
		$urlField->setPattern('^(https?://.{1,}\..{1,}|.{1,}@.{1,}\..{1,})$');
		$urlField->setAutocompleteAttribute(AutocompleteValues::ON);
		$urlField->addError(90704, ErrorCodes::ERR_90704);
		$urlField->setMissingErrorCode(90704);
		$urlField->addError(90705, ErrorCodes::ERR_90705);
		$urlField->setInvalidErrorCode(90705);
		$urlField->addError(90706, ErrorCodes::ERR_90706);
		$urlField->addError(90707, ErrorCodes::ERR_90707);
		$urlField->addError(90708, ErrorCodes::ERR_90708);
		$urlField->addError(90709, ErrorCodes::ERR_90709);
		$urlField->addError(90710, ErrorCodes::ERR_90710);
		$form->addField($urlField);

		return $form;
	}

	/**
	 * Adding a social media network (link) form
	 * 
	 * See /Dashboard and other similar for fomr usage
	 * @return Form Form for adding a social network via. a link
	 */
	public static function getAddNetworkOtherForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/social_media/add_other/");
		$form->setButtonText("ADD");
		$form->setPrimary(false);

		$completionAction = new CallUserFuncCompletionAction();
		$completionAction->setFunc("addSocialMediaChip");
		$form->setCompletionAction($completionAction);

		$destField = new HiddenInputField();
		$destField->setDistinguisher("dest");
		$destField->setHiddenInputId("social-dest-type");
		$form->addField($destField);

		$typeField = new SelectField();
		$typeField->setDistinguisher("type");
		$typeField->setLabel("Social Network");
		$typeField->setOptions(SocialMedia::getOtherNetworkAddSelectArray());
		$typeField->setRequired(true);
		$typeField->setAutocompleteAttribute(AutocompleteValues::ON);
		$typeField->addError(90712, ErrorCodes::ERR_90712);
		$typeField->setMissingErrorCode(90712);
		$typeField->addError(90713, ErrorCodes::ERR_90713);
		$typeField->setInvalidErrorCode(90713);
		$form->addField($typeField);

		$labelField = new TextField();
		$labelField->setDistinguisher("label");
		$labelField->setLabel("Label");
		$labelField->setRequired(true);
		$labelField->setPattern('^.{2,64}$');
		$labelField->setAutocompleteAttribute(AutocompleteValues::ON);
		$labelField->addError(90702, ErrorCodes::ERR_90702);
		$labelField->setMissingErrorCode(90702);
		$labelField->addError(90703, ErrorCodes::ERR_90703);
		$labelField->setInvalidErrorCode(90703);
		$form->addField($labelField);

		return $form;
	}
}
