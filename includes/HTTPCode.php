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
		201 => "Created",
		202 => "Deferred",
		301 => "Moved",
		302 => "Temporary Redirect",
		304 => "Not Modified",
		400 => "Bad Request",
		401 => "Unauthorized",
		403 => "Forbidden",
		404 => "Not Found",
		405 => "Bad Method",
		418 => "I'm a Teapot",
		429 => "Rate Limited",
		500 => "Unknown Error",
		501 => "Unimplemented",
		503 => "Maintenance",
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
