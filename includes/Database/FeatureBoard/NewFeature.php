<?php

namespace Catalyst\Database\FeatureBoard;

class NewFeature {
	public const SUCCESS = 0;
	public const NAME_INVALID = 1;
	public const GROUP_INVALID = 2;
	public const INTRO_INVALID = 3;
	public const PROPOSAL_INVALID = 4;
	public const ACKNOWLEDGEMENT_INVALID = 5;
	public const FUTURE_INVALID = 6;
	public const SEARCH_INVALID = 7;
	public const ERROR_UNKNOWN = 8;

	public const PHRASES = [
		self::SUCCESS => "Success",
		self::NAME_INVALID => "Invalid name",
		self::GROUP_INVALID => "Invalid grouping",
		self::INTRO_INVALID => "Invalid introduction",
		self::PROPOSAL_INVALID => "Invalid proposal",
		self::ACKNOWLEDGEMENT_INVALID => "Invalid acknowledgement",
		self::FUTURE_INVALID => "Invalid future scope",
		self::SEARCH_INVALID => "Check the box to continue",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "newfeature",
				"ajax" => true,
				"eval" => 'window.location = window.location = "../Item/"+JSON.parse(response).message;;',
				"method" => "POST",
				"handler" => 'handler.php',
				"button" => "submit",
				"additional_cases" => [],
				"additional_fields" => [],
				"success" => self::PHRASES[self::SUCCESS],
			],
			[
				"name" => "name",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "Name",
				"pattern" => ['^.{2,255}$', "Between 2 and 255 characters"],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::ERROR_UNKNOWN]],
				"error_code" => [self::ERROR_UNKNOWN],
			],
			[
				"name" => "group",
				"wrapper_classes" => "col s12",
				"type" => "select",
				"options" => \Catalyst\Database\FeatureBoard\Groups::get(),
				"label" => "Group",
				"required" => true,
				"error_text" => [self::PHRASES[self::ERROR_UNKNOWN]],
				"error_code" => [self::ERROR_UNKNOWN],
			],
			[
				"name" => "intro",
				"wrapper_classes" => "col s12",
				"type" => "markdown-textarea",
				"label" => "Introduction",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::ERROR_UNKNOWN]],
				"error_code" => [self::ERROR_UNKNOWN],
				"other_attributes" => [
					"placeholder" => "The elevator pitch for your request.  Should only be a paragraph or so, and summarily tell what your request is asking for.  Please write a good introduction, it is what will convince the reader to continue to read your proposal.",
				],
			],
			[
				"name" => "proposal",
				"wrapper_classes" => "col s12",
				"type" => "markdown-textarea",
				"label" => "Proposal",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::ERROR_UNKNOWN]],
				"error_code" => [self::ERROR_UNKNOWN],
				"other_attributes" => [
					"placeholder" => "All the features, explainations, and examples of the proposal.  Explain hows the proposal brings substantial value and why it should be considered for inclusion.",
				],
			],
			[
				"name" => "acknowledgement",
				"wrapper_classes" => "col s12",
				"type" => "markdown-textarea",
				"label" => "Acknowledgement",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::ERROR_UNKNOWN]],
				"error_code" => [self::ERROR_UNKNOWN],
				"other_attributes" => [
					"placeholder" => "What might some people have against your feature?  What are some arguments that could be made about why it shouldn't be included?  List them here, and, if possible, a rebuttal.  This proves that you have thoroughly thought about the feature.",
				],
			],
			[
				"name" => "future",
				"wrapper_classes" => "col s12",
				"type" => "markdown-textarea",
				"label" => "Future Scope",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::ERROR_UNKNOWN]],
				"error_code" => [self::ERROR_UNKNOWN],
				"other_attributes" => [
					"placeholder" => "How could this be expanded on or changed over time?  What could be some additional extensions that build off your request?  Not required, but recommended.",
				],
			],
			[
				"name" => "haschecked",
				"wrapper_classes" => "col s12",
				"type" => "checkbox",
				"label" => "I have searched the existing board list and found no similar proposals",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::ERROR_UNKNOWN]],
				"error_code" => [self::ERROR_UNKNOWN],
			],
		];
	}

	public static function new(\Catalyst\User\User $user, string $name, string $group, string $intro, string $proposal, string $acknowledgement, string $future, string &$url) : int {
		$stmt = $GLOBALS["dbh"]->prepare("INSERT INTO `".DB_TABLES["feature_board_items"]."` (`NAME`,`AUTOGEN_URL`,`CREATED_TS`,`AUTHOR_ID`,`GROUP`,`INTRODUCTION`,`PROPOSAL`,`ACKNOWLEDGEMENT`,`FUTURE_SCOPE`) VALUES (:NAME,:AUTOGEN_URL,CURRENT_TIMESTAMP,:AUTHOR_ID,:GROUP,:INTRODUCTION,:PROPOSAL,:ACKNOWLEDGEMENT,:FUTURE_SCOPE);");
		$stmt->bindParam(":NAME", $name);
		// turns a name into dash-case
		$url = substr(
			implode(
				"-",
				explode(
					" ",
					implode(
						"",
						array_filter(
							str_split(
								strtolower($name)
							), 
							function($in) {
								return preg_match('/[a-z0-9 ]/', $in);
							}
						)
					)
				)
			),
			0,
			64
		);
		$stmt->bindParam(":AUTOGEN_URL", $url);
		$aid = $user->getId();
		$stmt->bindParam(":AUTHOR_ID", $aid);
		$stmt->bindParam(":GROUP", $group);
		$stmt->bindParam(":INTRODUCTION", $intro);
		$stmt->bindParam(":PROPOSAL", $proposal);
		$stmt->bindParam(":ACKNOWLEDGEMENT", $acknowledgement);
		$stmt->bindParam(":FUTURE_SCOPE", $future);

		if (!$stmt->execute()) {
			return self::ERROR_UNKNOWN;
		}

		return self::SUCCESS;
	}
}
