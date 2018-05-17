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
		200 => "(ur) OK",
		201 => "Created (a thing)",
		202 => "Deferred (what)",
		204 => "No Content (spoopy)",
		301 => "Moved (bye bye)",
		302 => "Temporary Redirect (im hiding over here!)",
		304 => "Not Modified (haha)",
		400 => "Bad Request (cmon do it right)",
		401 => "Unauthorized (login)",
		403 => "Forbidden (go away)",
		404 => "Not Found (stop poking around)",
		405 => "Bad Method (wyd ffs)",
		418 => "I'm a Teapot (:eyes:)",
		429 => "Rate Limited (slow the fuck down)",
		500 => "Unknown Error (whoopsies)",
		501 => "Unimplemented (im too lazy for this)",
		503 => "Maintenance (try again later we doin stuffs)",
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
	public static function set(int $code) : void {
		if (!array_key_exists($code, self::CODE_MAP)) {
			throw new \InvalidArgumentException($code." is not a known HTTP status code");
		}
		if (php_sapi_name() == "cli") {
			return;
		}
		header($_SERVER["SERVER_PROTOCOL"]." ".$code." ".self::CODE_MAP[$code]);
	}
}
