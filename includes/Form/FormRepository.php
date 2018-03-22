<?php

namespace Catalyst\Form;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Character\Character;
use \Catalyst\Form\CompletionAction\{
	AutoClosingModalCompletionAction,
	CallUserFuncCompletionAction,
	ConcreteRedirectCompletionAction,
	ConditionalCompletionAction,
	DynamicRedirectCompletionAction
};
use \Catalyst\Form\Field\{
	CaptchaField,
	CheckboxField,
	ColorField,
	ConfirmPasswordField,
	EmailField,
	HiddenInputField,
	ImageField,
	JSConfirmField,
	MarkdownField,
	MultipleImageField,
	MultipleImageWithNsfwCaptionAndInfoField,
	PasswordField,
	RawLabelCheckboxField,
	SelectField,
	StaticHTMLField,
	TextField};
use \Catalyst\Form\Form;
use \Catalyst\Integrations\SocialMedia;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Secrets;
use \Catalyst\Tokens;
use \Catalyst\User\{
	DeactivateFormTrait,
	EmailListFormTrait,
	EmailVerificationFormTrait,
	LoginFormTrait,
	RegisterFormTrait,
	SettingsFormTrait,
	TOTPLoginFormTrait,
	User};
use \ReflectionClass;

/**
 * Simply a repository of forms for the site.
 * 
 * Methods should not be defomed in this file, but in traits which are used here
 */
class FormRepository {
	/**
	 * Get a distinguisher from __FUNCTION__ (convert to dash case and remove get)
	 * 
	 * I like this method as it ensures unique names (you can't redefine a function!)
	 * (ok lets be honest this is php you can fucking redefine constants but whatever)
	 * 
	 * @param string $in Function name to get a distinguisher from
	 * @return string Dash-case formatted distinguisher
	 */
	public static function getDistinguisherFromFunctionName(string $in) : string {
		// remove get
		$withoutGet = preg_replace('/^get/', '', $in);
		// convert to dash-case
		$toDashCase = preg_replace('/([a-z])([A-Z])/', '\1-\2', $withoutGet);
		// force lowercase
		return strtolower($toDashCase);
	}

	use EmailListFormTrait;
	use LoginFormTrait;
	use TOTPLoginFormTrait;
	use RegisterFormTrait;
	use EmailVerificationFormTrait;
	use SettingsFormTrait;
	use DeactivateFormTrait;



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
		$destField->setSelector("#social-dest-type");
		$form->addField($destField);

		$labelField = new TextField();
		$labelField->setDistinguisher("label");
		$labelField->setLabel("Label");
		$labelField->setRequired(true);
		$labelField->setPattern('^.{2,64}$');
		$labelField->addError(90702, ErrorCodes::ERR_90702);
		$labelField->setMissingErrorCode(90702);
		$labelField->addError(90703, ErrorCodes::ERR_90703);
		$labelField->setInvalidErrorCode(90703);
		$form->addField($labelField);

		$urlField = new TextField();
		$urlField->setDistinguisher("url");
		$urlField->setLabel("URL or email");
		$urlField->setRequired(true);
		$urlField->setPattern('^(https?://.{2,}\..{2,}|.{2,}@.{2,}\..{2,})$');
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
		$destField->setSelector("#social-dest-type");
		$form->addField($destField);

		$typeField = new SelectField();
		$typeField->setDistinguisher("type");
		$typeField->setLabel("Social Network");
		$typeField->setOptions(SocialMedia::getOtherNetworkAddSelectArray());
		$typeField->setRequired(true);
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
		$labelField->addError(90702, ErrorCodes::ERR_90702);
		$labelField->setMissingErrorCode(90702);
		$labelField->addError(90703, ErrorCodes::ERR_90703);
		$labelField->setInvalidErrorCode(90703);
		$form->addField($labelField);

		return $form;
	}

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
		$nameField->addError(90801, ErrorCodes::ERR_90801);
		$nameField->setMissingErrorCode(90801);
		$nameField->addError(90802, ErrorCodes::ERR_90802);
		$nameField->setInvalidErrorCode(90802);
		$form->addField($nameField);

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
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
		$tokenField->setSelector("#character-token");
		$form->addField($tokenField);

		$nameField = new TextField();
		$nameField->setDistinguisher("name");
		$nameField->setLabel("Name");
		$nameField->setRequired(true);
		$nameField->setPattern('^.{2,255}$');
		$nameField->setMaxLength(255);
		$nameField->addError(91001, ErrorCodes::ERR_91001);
		$nameField->setMissingErrorCode(91001);
		$nameField->addError(91002, ErrorCodes::ERR_91002);
		$nameField->setInvalidErrorCode(91002);
		if (!is_null($character)) {
			$nameField->setPrefilledValue($character->getName());
		}
		$form->addField($nameField);

		$descriptionField = new MarkdownField();
		$descriptionField->setDistinguisher("description");
		$descriptionField->setLabel("Description");
		$descriptionField->setRequired(true);
		$descriptionField->addError(91003, ErrorCodes::ERR_91003);
		$descriptionField->setMissingErrorCode(91003);
		$descriptionField->addError(91004, ErrorCodes::ERR_91004);
		$descriptionField->setInvalidErrorCode(91004);
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
		$colorField->addError(91008, ErrorCodes::ERR_91008);
		$colorField->setMissingErrorCode(91008);
		$colorField->addError(91009, ErrorCodes::ERR_91009);
		$colorField->setInvalidErrorCode(91009);
		$form->addField($colorField);

		$publicCheckboxField = new CheckboxField();
		$publicCheckboxField->setDistinguisher("public");
		$publicCheckboxField->setLabel("Make this character public");
		$publicCheckboxField->setRequired(false);
		$publicCheckboxField->addError(91010, ErrorCodes::ERR_91010);
		$publicCheckboxField->setMissingErrorCode(91010);
		$publicCheckboxField->addError(91011, ErrorCodes::ERR_91011);
		$publicCheckboxField->setInvalidErrorCode(91011);
		if (!is_null($character)) {
			$publicCheckboxField->setPrefilledValue($character->isPublic());
		}
		$form->addField($publicCheckboxField);

		$publicNotice = new StaticHTMLField();
		$publicNotice->setHtml('<p class="col s12 no-margin">If this character is public, anyone can see it on your profile and access it with its link.  Otherwise, only you and artists you commission may see it.</p>');
		$form->addField($publicNotice);

		return $form;
	}

	/**
	 * Get the form used to delete a character
	 * 
	 * @return Form
	 */
	public static function getDeleteCharacterForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/character/delete/");
		$form->setButtonText("DELETE");
		$form->setPrimary(false);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Character");
		$form->setCompletionAction($completionAction);

		$tokenField = new HiddenInputField();
		$tokenField->setDistinguisher("token");
		$tokenField->setSelector("#character-token");
		$form->addField($tokenField);

		$confirmField = new JSConfirmField();
		$confirmField->setDistinguisher("confirm");
		$confirmField->setRequired(true);
		$confirmField->setPrompt("Are you sure you want to delete this character?");
		$form->addField($confirmField);

		return $form;
	}

	/**
	 * Get the form used to un-delete an artist page
	 * 
	 * @return Form
	 */
	public static function getUndeleteArtistPageForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/artist/undelete/");
		$form->setButtonText("REACTIVATE");
		$form->setPrimary(false);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Artist/Edit");
		$form->setCompletionAction($completionAction);

		return $form;
	}

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
		// these lost some clarity as what means what due to ImageField
		$profilePictureField->addError(91208, ErrorCodes::ERR_91208);
		$profilePictureField->setMissingErrorCode(91208);
		$profilePictureField->addError(91209, ErrorCodes::ERR_91209);
		$profilePictureField->setInvalidErrorCode(91209);
		$profilePictureField->addError(91210, ErrorCodes::ERR_91210);
		$profilePictureField->setTooLargeErrorCode(91210);
		$form->addField($profilePictureField);

		$noNsfwWarning = new StaticHTMLField();
		$noNsfwWarning->setHtml('<p class="col s12 no-top-margin">Artist\'s profile images may <strong>not</strong> be mature or explicit.</p>');
		$form->addField($noNsfwWarning);

		$colorField = new ColorField();
		$colorField->setDistinguisher("color");
		$colorField->setLabel("Color");
		$colorField->setRequired(true);
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

	/**
	 * Get the form used to delete an artist page
	 * 
	 * @return Form
	 */
	public static function getDeleteArtistPageForm() : Form {
		$form = new Form();

		$form->setDistinguisher(self::getDistinguisherFromFunctionName(__FUNCTION__)); // get-dash-case from camelCase
		$form->setMethod(Form::POST);
		$form->setEndpoint("internal/artist/delete/");
		$form->setButtonText("DELETE");
		$form->setPrimary(false);

		$completionAction = new ConcreteRedirectCompletionAction();
		$completionAction->setRedirectUrl("Dashboard");
		$form->setCompletionAction($completionAction);

		$confirmField = new JSConfirmField();
		$confirmField->setDistinguisher("confirm");
		$confirmField->setRequired(true);
		$confirmField->setPrompt("Are you sure you want to delete your page?  This will PERMANENTLY delete all commission types and other information.");
		$form->addField($confirmField);

		return $form;
	}

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
		$urlField->setPattern('^[A-Za-z0-9._-]{3,254}[A-Za-z0-9_-]$');
		$urlField->setDisallowed(["Edit", "New", "ToS", "CommissionTypes"]);
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
		// these lost some clarity as what means what due to ImageField
		$profilePictureField->addError(91408, ErrorCodes::ERR_91408);
		$profilePictureField->setMissingErrorCode(91408);
		$profilePictureField->addError(91409, ErrorCodes::ERR_91409);
		$profilePictureField->setInvalidErrorCode(91409);
		$profilePictureField->addError(91410, ErrorCodes::ERR_91410);
		$profilePictureField->setTooLargeErrorCode(91410);
		$form->addField($profilePictureField);

		$noNsfwWarning = new StaticHTMLField();
		$noNsfwWarning->setHtml('<p class="col s12 no-top-margin">Artist\'s profile images may <strong>not</strong> be mature or explicit.</p>');
		$form->addField($noNsfwWarning);

		$colorField = new ColorField();
		$colorField->setDistinguisher("color");
		$colorField->setLabel("Color");
		$colorField->setRequired(true);
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

	/**
	 * Get all Forms functions defined in the repository
	 * @return Form[] All forms in the repository
	 */
	public static function getAllForms() : array {
		$reflectedClass = new ReflectionClass(__CLASS__);
		$classMethods = $reflectedClass->getMethods();

		$usedTraits = $reflectedClass->getTraits();
		foreach ($usedTraits as $trait) {
			$classMethods += $trait->getMethods();
		}

		$forms = [];
		foreach ($classMethods as $method) {
			if ($method->getReturnType()->getName() == Form::class) {
				$forms[] = call_user_func([__CLASS__, $method->getName()]);
			}
		}

		return $forms;
	}
}
