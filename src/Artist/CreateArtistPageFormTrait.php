<?php

namespace Catalyst\Artist;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues,ColorField,ImageField,MarkdownField,StaticHTMLField,TextField};
use \Catalyst\Form\Form;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\User\User;

/**
 * create artist page form
 */
trait CreateArtistPageFormTrait {
	/**
	 * Get the form used to create an artist page
	 * 
	 * @return Form
	 */
	public static function getCreateArtistPageForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/artist/create/");
		$form->setButtonText("CREATE");
		$form->setPrimary(true);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Artist");
		$form->setCompletionAction($completionAction);

		$nameField = new TextField();
		$nameField->setDistinguisher("name");
		$nameField->setLabel("Name");
		$nameField->setRequired(true);
		$nameField->setMaxLength(255);
		$nameField->setPattern('^.{2,255}$');
		$nameField->setAutocompleteAttribute(AutocompleteValues::NICKNAME);
		$nameField->addError(91201, ErrorCodes::ERR_91201);
		$nameField->setMissingErrorCode(91201);
		$nameField->addError(91202, ErrorCodes::ERR_91202);
		$nameField->setInvalidErrorCode(91202);
		$form->addField($nameField);

		$urlField = new TextField();
		$urlField->setDistinguisher("url");
		$urlField->setLabel("URL");
		$urlField->setRequired(true);
		$urlField->setMaxLength(255);
		$urlField->setPattern('^[A-Za-z0-9._-]{3,254}[A-Za-z0-9_-]$');
		$urlField->setDisallowed(["Edit", "New", "ToS", "CommissionTypes"]);
		$urlField->setHelperText("Between 4 and 255 characters, containing letters, numbers, dashes, underscores, and dots (not at the end)");
		$urlField->setAutocompleteAttribute(AutocompleteValues::USERNAME);
		$urlField->addError(91203, ErrorCodes::ERR_91203);
		$urlField->setMissingErrorCode(91203);
		$urlField->addError(91204, ErrorCodes::ERR_91204);
		$urlField->setInvalidErrorCode(91204);
		$urlField->addError(91205, ErrorCodes::ERR_91205);
		$form->addField($urlField);

		$urlSample = new StaticHTMLField();
		
		$urlSampleHtml = '';

		$urlSampleHtml .= '<p';
		$urlSampleHtml .= ' class="col s12 no-top-margin"';
		$urlSampleHtml .= '>';

		$urlSampleHtml .= 'This will be the link to your page: ';
		
		$urlSampleHtml .= '<strong';
		$urlSampleHtml .= ' id="artist-page-url-sample"';
		$urlSampleHtml .= ' data-base="'.htmlspecialchars((preg_replace('/New\/?$/', '', UniversalFunctions::getRequestUrl()))).'"';
		$urlSampleHtml .= '>';
		
		$urlSampleHtml .= htmlspecialchars((preg_replace('/New\/?$/', '', UniversalFunctions::getRequestUrl())));
		
		$urlSampleHtml .= '</strong>';
		
		$urlSampleHtml .= '</p>';
		
		$urlSample->setHtml($urlSampleHtml);
		
		$form->addField($urlSample);

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
		$descriptionField->setAutocompleteAttribute(AutocompleteValues::OFF);
		$descriptionField->addError(91206, ErrorCodes::ERR_91206);
		$descriptionField->setMissingErrorCode(91206);
		$descriptionField->addError(91207, ErrorCodes::ERR_91207);
		$descriptionField->setInvalidErrorCode(91207);
		$form->addField($descriptionField);

		$profilePictureField = new ImageField();
		$profilePictureField->setDistinguisher("image");
		$profilePictureField->setLabel("Image");
		$profilePictureField->setRequired(false);
		$profilePictureField->setMaxHumanSize('10MB');
		$profilePictureField->setHelperText("Artist's profile images may not be mature or explicit.");
		$profilePictureField->setAutocompleteAttribute(AutocompleteValues::PHOTO);
		// these lost some clarity as what means what due to ImageField
		$profilePictureField->addError(91208, ErrorCodes::ERR_91208);
		$profilePictureField->setMissingErrorCode(91208);
		$profilePictureField->addError(91209, ErrorCodes::ERR_91209);
		$profilePictureField->setInvalidErrorCode(91209);
		$profilePictureField->addError(91210, ErrorCodes::ERR_91210);
		$profilePictureField->setTooLargeErrorCode(91210);
		$form->addField($profilePictureField);

		$colorField = new ColorField();
		$colorField->setDistinguisher("color");
		$colorField->setLabel("Color");
		$colorField->setRequired(true);
		$colorField->setAutocompleteAttribute(AutocompleteValues::ON);
		$colorField->addError(91211, ErrorCodes::ERR_91211);
		$colorField->setMissingErrorCode(91211);
		$colorField->addError(91212, ErrorCodes::ERR_91212);
		$colorField->setInvalidErrorCode(91212);
		if (User::isLoggedIn()) {
			$colorField->setPrefilledValue($_SESSION["user"]->getColor());
		}
		$form->addField($colorField);

		$tosField = new MarkdownField();
		$tosField->setDistinguisher("tos");
		$tosField->setLabel("Terms of Service");
		$tosField->setRequired(true);
		$tosField->setAutocompleteAttribute(AutocompleteValues::OFF);
		$tosField->addError(91213, ErrorCodes::ERR_91213);
		$tosField->setMissingErrorCode(91213);
		$tosField->addError(91214, ErrorCodes::ERR_91214);
		$tosField->setInvalidErrorCode(91214);
		$form->addField($tosField);

		$tosNote = new StaticHTMLField();
		
		$str = '';

		$str .= '<p';
		$str .= ' class="col s12 no-margin"';
		$str .= '>';
		$str .= 'We ';
		$str .= '<strong>highly</strong>';
		$str .= ' suggest that any creator, no matter what, have a ToS.';
		$str .= '</p>';

		$str .= '<p';
		$str .= ' class="col s12 no-margin"';
		$str .= '>';
		$str .= 'We recommend including at least the following items: what you will not do, payment information, refund information, shipping information, sharing of finished art, commercial use, and changes to the finished product.';
		$str .= '</p>';

		$str .= '<p';
		$str .= ' class="col s12 no-top-margin"';
		$str .= '>';
		$str .= 'Want ideas for what to include?  Check out our <a href="'.ROOTDIR.'Help/ToSGuide/">Terms of Service Guide</a>!';
		$str .= '</p>';

		$tosNote->setHtml($str);
		$form->addField($tosNote);

		return $form;
	}
}
