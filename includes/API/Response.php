<?php

namespace Catalyst\API;

use \Catalyst\{Controller, HTTPCode};

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
		echo json_encode([
			"error" => false,
			"http_code" => HTTPCode::get(),
			"error_code" => 0,
			"message" => $message,
			"data" => $data,
		], Controller::isDevelMode() ? JSON_PRETTY_PRINT : 0);
		if (!Endpoint::isInternalEndpoint()) {
			$_SESSION = [];
		}
		die();
	}

	/**
	 * Return an error response
	 * 
	 * @param int $code Endpoint-specific error code
	 * @param string $message Message which describes the response
	 * @param array $data Additional data
	 */
	public static function sendErrorResponse(int $code, string $message, array $data=[]) {
		echo json_encode([
			"error" => true,
			"http_code" => HTTPCode::get(),
			"error_code" => $code,
			"message" => $message,
			"data" => $data,
			"_debug" => [
				"_trace" => Controller::getTrace(false),
				"_request" => (isset($_REQUEST)) ? $_REQUEST : [],
				"_files" => (isset($_FILES)) ? $_FILES : [],
				"_session" => $_SESSION
			]
		], Controller::isDevelMode() ? JSON_PRETTY_PRINT : 0);
		if (!Endpoint::isInternalEndpoint()) {
			$_SESSION = [];
		}
		trigger_error("API Error RESPONSE given: ".$code." ".$message." ".serialize($data));
		die();
	}
}
