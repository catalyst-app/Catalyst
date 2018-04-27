<?php

namespace Catalyst\Form;

use \Catalyst\API\ErrorCodes;
use \Catalyst\Artist\{
	CreateArtistPageFormTrait,
	EditArtistPageFormTrait,
	DeleteArtistPageFormTrait,
	UndeleteArtistPageFormTrait};
use \Catalyst\Character\{
	Character,
	DeleteCharacterFormTrait,
	EditCharacterFormTrait,
	NewCharacterFormTrait};
use \Catalyst\CommissionType\NewCommissionTypeFormTrait;
use \Catalyst\Form\Form;
use \Catalyst\Integrations\{
	SocialMedia,
	SocialMediaAdditionFormsTrait};
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

	use SocialMediaAdditionFormsTrait;

	use NewCharacterFormTrait;
	use EditCharacterFormTrait;
	use DeleteCharacterFormTrait;

	use UndeleteArtistPageFormTrait;
	use CreateArtistPageFormTrait;
	use DeleteArtistPageFormTrait;
	use EditArtistPageFormTrait;

	use NewCommissionTypeFormTrait;

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
