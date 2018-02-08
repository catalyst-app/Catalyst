<?php

namespace Catalyst\Database;

/**
 * Basic interface which contains the functions required in order to make the query addition
 */
interface QueryAdditionInterface {
	/**
	 * Get the parameters to bind
	 * 
	 * @return array Parameters to bind to the Query
	 */
	public function getParamtersToBind() : array;
	/**
	 * Get the query string
	 * 
	 * @return string Query string, MUST include the WHERE/JOIN/etc
	 */
	public function getQueryString() : string;
}
