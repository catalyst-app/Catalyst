<?php

namespace Catalyst;

/**
 * Class to set HTTP code header and other similar utilities
 */
class HTTPCode {
	/**
	 * Mapping of code to message
	 */
	public const CODE_MAP = [
		200 => "OK",
		201 => "CREATED",
		202 => "DEFERRED",
		301 => "MOVED",
		304 => "NOT_MODIFIED",
		400 => "BAD_REQUEST",
		401 => "UNAUTHORIZED",
		403 => "FORBIDDEN",
		404 => "NOT_FOUND",
		405 => "BAD_METHOD",
		418 => "TEAPOT",
		429 => "RATE_LIMITED",
		500 => "ERROR",
		501 => "UNIMPLEMENTED",
		503 => "MAINTENANCE",
	];

	/**
	 * Get the current HTTP response code
	 * 
	 * @return int the code
	 */
	public static function get() : int {
		return http_response_code();
	}

	/**
	 * Set the HTTP response code
	 * 
	 * @param int $code the new code
	 * @throws InvalidArgumentException on invalid code
	 */
	public static function set(int $code) {
		if (!array_key_exists($code, self::CODE_MAP)) {
			throw new \InvalidArgumentException($code." is not a known HTTP status code");
		}
		header($_SERVER["SERVER_PROTOCOL"]." ".$code." ".self::CODE_MAP[$code]);
	}
}
