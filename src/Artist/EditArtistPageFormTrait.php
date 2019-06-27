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
		$nameField->setCustomErrorMessage("patternMismatch", "Please keep your name between 2 and 255 characters.");
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
		$urlField->setCustomErrorMessage("patternMismatch", "Please ensure the value is at least four characters and that you are only using letters, numbers, and the symbols ._- (and that there is not a period at the end)");
		$urlField->setCustomErrorMessage("urlInUse", "This URL is already in use by another user.  Please try something else.");
		if ($isArtist) {
			$urlField->setPrefilledValue($artist->getUrl());
		}
		$form->addField($urlField);

		$urlSample = '';
		$urlSample .= '<p class="col s12 no-top-margin">';
		$urlSample .= 'This will be the link to your page: ';
		$urlSample .= '<strong id="artist-page-url-sample" data-base="'.htmlspecialchars(preg_replace('/New\/?$/', '', UniversalFunctions::getRequestUrl())."").'"';
		$urlSample .= '>';
		$urlSample .= htmlspecialchars(preg_replace('/New\/?$/', '', UniversalFunctions::getRequestUrl())."");
		if ($isArtist) {
			$urlSample .= htmlspecialchars($artist->getUrl()).'/';
		}
		$urlSample .= '</strong>';
		$urlSample .= '</p>';
		
		$form->addStaticHtml($urlSample);

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
		$descriptionField->setAutocompleteAttribute(AutocompleteValues::OFF);
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
		if ($isArtist) {
			$colorField->setPrefilledValue($artist->getColor());
		}
		$form->addField($colorField);

		$tosField = new MarkdownField();
		$tosField->setDistinguisher("tos");
		$tosField->setLabel("Terms of Service");
		$tosField->setRequired(true);
		$tosField->setAutocompleteAttribute(AutocompleteValues::OFF);
		if ($isArtist) {
			$tosField->setPrefilledValue($artist->getCurrentTosWithoutDate());
		}
		$form->addField($tosField);

		$tosNote = '';
		$tosNote .= '<p class="col s12 no-margin">We <strong>highly</strong> suggest that any creator, no matter what, have a ToS.</p>';
		$tosNote .= '<p class="col s12 no-margin">We recommend including at least the following items: what you will not do, payment information, refund information, shipping information, sharing of finished art, commercial use, and changes to the finished product.</p>';
		$tosNote .= '<p class="col s12 no-top-margin">Want ideas for what to include?  Check out our <a href="'.ROOTDIR.'Help/ToSGuide/">Terms of Service Guide</a>!</p>';

		$form->addStaticHtml($tosNote);

		return $form;
	}
}
