<?php

namespace Catalyst\Database\FeatureBoard;

class NewFeature {
	public const SUCCESS = 0;
	public const COMMENT_INVALID = 1;
	public const ERROR_UNKNOWN = 2;

	public const PHRASES = [
		self::SUCCESS => "Success",
		self::COMMENT_INVALID => "Invalid comment",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "newcomment",
				"ajax" => true,
				"eval" => 'window.location = "";',
				"method" => "POST",
				"handler" => '"+$("html").attr("data-rootdir")+"FeatureBoard/comment.php',
				"button" => "create",
				"additional_cases" => [],
				"additional_fields" => [
					"feature" => '$(".meta-feature-id").attr("data-id")'
				],
				"success" => self::PHRASES[self::SUCCESS],
			],
			[
				"name" => "comment",
				"wrapper_classes" => "col s12",
				"type" => "markdown-textarea",
				"label" => "Comment",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::COMMENT_INVALID]],
				"error_code" => [self::COMMENT_INVALID],
			],
		];
	}
}
