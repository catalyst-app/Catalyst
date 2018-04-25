<?php

namespace Catalyst\Artist;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Form\CompletionAction\ConcreteRedirectCompletionAction;
use \Catalyst\Form\Field\{AutocompleteValues,ColorField,ImageField,MarkdownField,StaticHTMLField,TextField};
use \Catalyst\Form\Form;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\User\User;

/**
 * edit artist page form
 */
trait EditArtistPageFormTrait {
	/**
	 * Get the form used to edit an artist page
	 * 
	 * @return Form
	 */
	public static function getEditArtistPageForm() : Form {
		$form = new Form();

		$isArtist = User::isLoggedIn() && $_SESSION["user"]->isArtist();
		$artist = $isArtist ? $_SESSION["user"]->getArtistPage() : null;

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/artist/edit/");
		$form->setButtonText("SAVE");
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
		$nameField->addError(91401, ErrorCodes::ERR_91401);
		$nameField->setMissingErrorCode(91401);
		$nameField->addError(91402, ErrorCodes::ERR_91402);
		$nameField->setInvalidErrorCode(91402);
		if ($isArtist) {
			$nameField->setPrefilledValue($artist->getName());
		}
		$form->addField($nameField);

		$urlField = new TextField();
		$urlField->setDistinguisher("url");
		$urlField->setLabel("URL");
		$urlField->setRequired(true);
		$urlField->setMaxLength(255);
		$urlField->setAutocompleteAttribute(AutocompleteValues::USERNAME);
		$urlField->setPattern('^[A-Za-z0-9._-]{3,254}[A-Za-z0-9_-]$');
		$urlField->setDisallowed(["Edit", "New", "ToS", "CommissionTypes"]);
		$urlField->setHelperText("Between 4 and 255 characters, containing letters, numbers, dashes, underscores, and dots (not at the end)");
		$urlField->addError(91403, ErrorCodes::ERR_91403);
		$urlField->setMissingErrorCode(91403);
		$urlField->addError(91404, ErrorCodes::ERR_91404);
		$urlField->setInvalidErrorCode(91404);
		$urlField->addError(91405, ErrorCodes::ERR_91405);
		if ($isArtist) {
			$urlField->setPrefilledValue($artist->getUrl());
		}
		$form->addField($urlField);

		$urlSample = new StaticHTMLField();
		
		$urlSampleHtml = '';

		$urlSampleHtml .= '<p';
		$urlSampleHtml .= ' class="col s12 no-top-margin"';
		$urlSampleHtml .= '>';

		$urlSampleHtml .= 'This will be the link to your page: ';
		
		$urlSampleHtml .= '<strong';
		$urlSampleHtml .= ' id="artist-page-url-sample"';
		$urlSampleHtml .= ' data-base="'.htmlspecialchars((preg_replace('/Edit\/?$/', '', UniversalFunctions::getRequestUrl()))).'"';
		$urlSampleHtml .= '>';
		
		$urlSampleHtml .= htmlspecialchars((preg_replace('/Edit\/?$/', '', UniversalFunctions::getRequestUrl())));

		if ($isArtist) {
			$urlSampleHtml .= htmlspecialchars($artist->getUrl()).'/';
		}
		
		$urlSampleHtml .= '</strong>';
		
		$urlSampleHtml .= '</p>';
		
		$urlSample->setHtml($urlSampleHtml);
		
		$form->addField($urlSample);

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
		$descriptionField->setAutocompleteAttribute(AutocompleteValues::OFF);
		$descriptionField->addError(91406, ErrorCodes::ERR_91406);
		$descriptionField->setMissingErrorCode(91406);
		$descriptionField->addError(91407, ErrorCodes::ERR_91407);
		$descriptionField->setInvalidErrorCode(91407);
		if ($isArtist) {
			$descriptionField->setPrefilledValue($artist->getDescription());
		}
		$form->addField($descriptionField);

		$profilePictureField = new ImageField();
		$profilePictureField->setDistinguisher("image");
		$profilePictureField->setLabel("Image");
		$profilePictureField->setRequired(false);
		$profilePictureField->setMaxHumanSize('10MB');
		$profilePictureField->setHelperText("Artist's profile images may not be mature or explicit.");
		$profilePictureField->setAutocompleteAttribute(AutocompleteValues::PHOTO);
		// these lost some clarity as what means what due to ImageField
		$profilePictureField->addError(91408, ErrorCodes::ERR_91408);
		$profilePictureField->setMissingErrorCode(91408);
		$profilePictureField->addError(91409, ErrorCodes::ERR_91409);
		$profilePictureField->setInvalidErrorCode(91409);
		$profilePictureField->addError(91410, ErrorCodes::ERR_91410);
		$profilePictureField->setTooLargeErrorCode(91410);
		$form->addField($profilePictureField);

		$colorField = new ColorField();
		$colorField->setDistinguisher("color");
		$colorField->setLabel("Color");
		$colorField->setRequired(true);
		$colorField->setAutocompleteAttribute(AutocompleteValues::ON);
		$colorField->addError(91411, ErrorCodes::ERR_91411);
		$colorField->setMissingErrorCode(91411);
		$colorField->addError(91412, ErrorCodes::ERR_91412);
		$colorField->setInvalidErrorCode(91412);
		if ($isArtist) {
			$colorField->setPrefilledValue($artist->getColor());
		}
		$form->addField($colorField);

		$tosField = new MarkdownField();
		$tosField->setDistinguisher("tos");
		$tosField->setLabel("Terms of Service");
		$tosField->setRequired(true);
		$tosField->setAutocompleteAttribute(AutocompleteValues::OFF);
		$tosField->addError(91413, ErrorCodes::ERR_91413);
		$tosField->setMissingErrorCode(91413);
		$tosField->addError(91414, ErrorCodes::ERR_91414);
		$tosField->setInvalidErrorCode(91414);
		if ($isArtist) {
			$tosField->setPrefilledValue($artist->getCurrentTosWithoutDate());
		}
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
