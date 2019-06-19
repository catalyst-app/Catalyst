<?php

namespace Catalyst\API;

use \Catalyst\{Controller, HTTPCode};
use \Catalyst\Database\Database;
use \Catalyst\Database\Query\AbstractQuery;
use \Catalyst\Page\UniversalFunctions;

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
	public static function sendSuccess(string $message, array $data=[]) : void {
		if (HTTPCode::get() > 400) {
			HTTPCode::set(200);
		}
		echo json_encode([
			"error" => false,
			"http_code" => HTTPCode::get(),
			"error_code" => 0,
			"message" => $message,
			"data" => $data,
			"_debug" => Controller::isDevelMode() ? [
				"_commit" => Controller::getCommit(),
				"_time" => microtime(true)-EXEC_START_TIME,
				"_queries" => AbstractQuery::getTotalQueries(),
				"_query_time" => Database::getTotalQueryTime(),
				"_memory" => UniversalFunctions::humanize(memory_get_peak_usage()),
			] : "PRODUCTION",
		], Controller::isDevelMode() ? JSON_PRETTY_PRINT : 0);
		if (Endpoint::isEndpoint() && !Endpoint::isInternalEndpoint()) {
			$_SESSION = [];
		}
		die();
	}

	/**
	 * Return an error response
	 * 
	 * @deprecated
	 * @param int $code Endpoint-specific error code
	 * @param string $message Message which describes the response
	 * @param array $data Additional data
	 */
	public static function sendErrorResponse(int $code, string $message, array $data=[]) : void {
		if (HTTPCode::get() < 300) {
			HTTPCode::set(400);
		}
		echo json_encode([
			"error" => true,
			"http_code" => HTTPCode::get(),
			"error_code" => $code,
			"message" => $message,
			"data" => $data,
			"_debug" => Controller::isDevelMode() ? [
				"_trace" => Controller::getTrace(true),
				"_get" => $_GET,
				"_post" => $_POST,
				"_files" => $_FILES,
				"_session" => $_SESSION,
				"_commit" => Controller::getCommit(),
				"_time" => microtime(true)-EXEC_START_TIME,
				"_queries" => AbstractQuery::getTotalQueries(),
				"_query_time" => Database::getTotalQueryTime(),
				"_memory" => UniversalFunctions::humanize(memory_get_peak_usage()),
			] : "PRODUCTION",
		], Controller::isDevelMode() ? JSON_PRETTY_PRINT : 0);
		if (strpos($_SERVER["SCRIPT_NAME"], "/api/internal/") === false) {
			trigger_error("API Error RESPONSE given: ".$code." ".$message." ".serialize($data));
		}
		if (Endpoint::isEndpoint() && !Endpoint::isInternalEndpoint()) {
			$_SESSION = [];
		}
		die();
	}

	/**
	 * Return an error response
	 * 
	 * @param string $location Distinguisher of the form-field or other location type
	 * @param string $errorType Type of error
	 * @param array $data Additional data
	 */
	public static function sendError(string $location, string $errorType, array $data=[]) : void {
		if (HTTPCode::get() < 300) {
			HTTPCode::set(400);
		}
		echo json_encode([
			"error" => true,
			"http_code" => HTTPCode::get(),
			"error_location" => $location,
			"error_type" => $errorType,
			"data" => $data,
			"_debug" => Controller::isDevelMode() ? [
				"_trace" => Controller::getTrace(true),
				"_get" => $_GET,
				"_post" => $_POST,
				"_files" => $_FILES,
				"_session" => $_SESSION,
				"_commit" => Controller::getCommit(),
				"_time" => microtime(true)-EXEC_START_TIME,
				"_queries" => AbstractQuery::getTotalQueries(),
				"_query_time" => Database::getTotalQueryTime(),
				"_memory" => UniversalFunctions::humanize(memory_get_peak_usage()),
			] : "PRODUCTION",
		], Controller::isDevelMode() ? JSON_PRETTY_PRINT : 0);
		if (strpos($_SERVER["SCRIPT_NAME"], "/api/internal/") === false) {
			trigger_error("API Error RESPONSE given: ".$distinguisher." ".$errorType." ".serialize($data));
		}
		if (Endpoint::isEndpoint() && !Endpoint::isInternalEndpoint()) {
			$_SESSION = [];
		}
		die();
	}
}
