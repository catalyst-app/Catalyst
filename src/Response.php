<?php

namespace Catalyst;

class Response {
	public static function generateSuccessResponse(int $httpCode, string $message) : string {
		return json_encode([
			"error" => false,
			"message" => $message,
			"http_code" => $httpCode
		], JSON_PRETTY_PRINT);
	}

	public static function generateErrorResponse(int $httpCode, int $code, string $error) : string {
		return json_encode([
			"error" => true,
			"message" => $error,
			"http_code" => $httpCode,
			"error_code" => $code,
			"_debug" => [
				"_trace" => Controller::getTrace(false),
				"_request" => (isset($_REQUEST)) ? $_REQUEST : [],
				"_files" => (isset($_FILES)) ? $_FILES : [],
				"_session" => $_SESSION
			]
		], JSON_PRETTY_PRINT);
	}

	public static function send200(string $message) {
		header($_SERVER["SERVER_PROTOCOL"]." 200 OK");
		die(self::generateSuccessResponse(200, $message));
	}

	public static function send201(string $message) {
		header($_SERVER["SERVER_PROTOCOL"]." 201 Created");
		die(self::generateSuccessResponse(201, $message));
	}

	public static function send401(int $code, string $message) {
		header($_SERVER["SERVER_PROTOCOL"]." 401 Bad Request");
		die(self::generateErrorResponse(401, $code, $message));
	}

	public static function send405(string $correctMethod) {
		header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed");
		die(self::generateErrorResponse(405, 405, $_SERVER["REQUEST_METHOD"]." is not a valid method for this ".$correctMethod." endpoint."));
	}

	public static function send500(string $message, int $code=0) {
		header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Server Error");
		die(self::generateErrorResponse(500, $code, $message));
	}
}
