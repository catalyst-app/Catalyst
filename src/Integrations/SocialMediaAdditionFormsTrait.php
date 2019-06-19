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
		$form->setResetOnSuccess(true);

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
		$labelField->setCustomErrorMessage("patternMismatch", "Please use at least two characters");
		$form->addField($labelField);

		$urlField = new TextField();
		$urlField->setDistinguisher("url");
		$urlField->setLabel("URL or email");
		$urlField->setRequired(true);
		$urlField->setPattern('^(https?://.{1,}\..{1,}|.{1,}@.{1,}\..{1,})$');
		$urlField->setAutocompleteAttribute(AutocompleteValues::ON);
		$urlField->setCustomErrorMessage("patternMismatch", "This looks a bit off, please make sure you are including https:// for a URL");
		$urlField->setCustomErrorMessage("disallowedDomain", "This domain is not allowed");
		$urlField->setCustomErrorMessage("ipAddress", "We do not allow direct IP addresses");
		$urlField->setCustomErrorMessage("invalidScheme", "Only http, https, and e-mail addresses are allowed");
		$urlField->setCustomErrorMessage("inlineAuthentication", "Embedded HTTP credentials are not allowed");
		$urlField->setCustomErrorMessage("invalidPort", "We only allow ports 80, 443, 8080, and 8081.  Contact support to unlock additional ports");
		$urlField->setCustomErrorMessage("javascriptScheme", "It's ".date("Y").", did you seriously expect that to work?");
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
		$form->setResetOnSuccess(true);

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
		$form->addField($labelField);

		return $form;
	}
}
