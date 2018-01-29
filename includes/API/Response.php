<?php

namespace Catalyst\API;

use \Catalyst\HTTPCode;

/**
 * Contains various utilities to standardize JSON responses
 */
class Response {
	/**
	 * Return a success response
	 * 
	 * @param string $message Message which describes the response
	 * @param array $data Additional data
	 */
	public static function sendSuccessResponse(string $message, array $data=[]) {
		return die(json_encode([
			"error" => false,
			"http_code" => HTTPCode::get(),
			"error_code" => 0,
			"message" => $message,
			"data" => $data,
		], DEVEL ? JSON_PRETTY_PRINT : 0));
	}

	/**
	 * Return an error response
	 * 
	 * @param int $code Endpoint-specific error code
	 * @param string $message Message which describes the response
	 * @param array $data Additional data
	 */
	public static function sendErrorResponse(int $code, string $message, array $data=[]) {
		return die(json_encode([
			"error" => true,
			"http_code" => HTTPCode::get(),
			"error_code" => $code,
			"message" => $message,
			"data" => $data,
			"_debug" => [
				"_trace" => array_map(function($in) { return "(".$in["line"].")"."->".$in["class"].$in["type"].$in["function"]; }, (new \Exception())->getTrace()),
				"_request" => (isset($_REQUEST)) ? $_REQUEST : [],
				"_files" => (isset($_FILES)) ? $_FILES : [],
				"_session" => $_SESSION
			]
		], DEVEL ? JSON_PRETTY_PRINT : 0));
	}
}
