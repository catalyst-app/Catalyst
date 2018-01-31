<?php

namespace Catalyst\API;

use \Catalyst\Database\{JoinClause, SelectQuery, Tables, WhereClause};
use \Catalyst\HTTPCode;

/**
 * Utility functions for an endpoint
 */
class Endpoint {
	/**
	 * Regex to check a Client header (ID,SECRET)
	 */
	const CLIENT_HEADER_REGEX = '/^([a-z0-9]{16}),([a-z0-9]{60})$/';
	/**
	 * Regex to check a User header (TOKEN,SECRET)
	 */
	const USER_HEADER_REGEX = '/^([a-z0-9]{40}),([a-z0-9]{60})$/';

	static $isEndpoint = false;

	/**
	 * Ran for every endpoint
	 */
	public static function init() {
		self::$isEndpoint = true;
		self::checkAuthorizationHeaders();
	}

	/**
	 * Get if the current page is an API endpoint
	 * 
	 * @return bool if the current page is an API endpoint
	 */
	public static function isApi() : bool {
		return self::$isEndpoint;
	}

	/**
	 * Check if the authorization headers are valid
	 * 
	 * @return bool If the headers are valid
	 */
	public static function checkAuthorizationHeaders() : bool {
		$headers = getallheaders();

		if (!array_key_exists("Client", $headers)) {
			HTTPCode::set(401);
			Response::sendErrorResponse(10001, "Client header was not passed.");
			return false;
		}

		if (!array_key_exists("User", $headers)) {
			HTTPCode::set(401);
			Response::sendErrorResponse(10002, "User header was not passed.");
			return false;
		}

		$clientKeys = [];
		if (!preg_match(self::CLIENT_HEADER_REGEX, $headers["Client"], $clientKeys)) {
			HTTPCode::set(401);
			Response::sendErrorResponse(10003, "Client header is invalid.");
			return false;
		}

		$userKeys = [];
		if (!preg_match(self::USER_HEADER_REGEX, $headers["User"], $userKeys)) {
			HTTPCode::set(401);
			Response::sendErrorResponse(10004, "User header is invalid.");
			return false;
		}

		if (!self::checkClientKeys($clientKeys[1], $clientKeys[2])) {
			HTTPCode::set(401);
			Response::sendErrorResponse(10005, "Client does not exist.");
			return false;
		}

		if (!self::checkUserKeys($clientKeys[1], $clientKeys[2], $userKeys[1], $userKeys[2])) {
			HTTPCode::set(401);
			Response::sendErrorResponse(10006, "User tokens are invalid.");
			return false;
		}

		return true;
	}

	/**
	 * Checks if the client keys exist in the database and are valid
	 * 
	 * @param string $clientId The app's client ID
	 * @param string $clientSecret The app's client secret
	 * @return bool if the keys are valid
	 */
	public static function checkClientKeys(string $clientId, string $clientSecret) : bool {
		$query = new SelectQuery();
		$query->setTable(Tables::API_KEYS);
		$query->addColumn("ID");
		
		$whereClause = new WhereClause();
		$whereClause->addToClause(["CLIENT_ID", "=", $clientId]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause(["CLIENT_SECRET", "=", $clientSecret]);
		$query->addAdditionalCapability($whereClause);

		$query->execute();

		return !empty($query->getResult());
	}

	/**
	 * Checks if the client keys exist and are with the user's keys in the database
	 * 
	 * @param string $clientId The app's client ID
	 * @param string $clientSecret The app's client secret
	 * @param string $userToken The user's token for the app
	 * @param string $userSecret The user's secret for the app
	 * @return bool if the keys are valid
	 */
	public static function checkUserKeys(string $clientId, string $clientSecret, string $userToken, string $userSecret) : bool {
		$query = new SelectQuery();
		$query->setTable(Tables::API_AUTHORIZATIONS);
		$query->addColumn([Tables::API_AUTHORIZATIONS,"ID"]);

		$joinClause = new JoinClause();
		$joinClause->setType(JoinClause::INNER);
		$joinClause->setJoinTable(Tables::API_KEYS);
		$joinClause->setCondition([[Tables::API_KEYS,"ID"],[Tables::API_AUTHORIZATIONS,"API_ID"]]);
		$query->addAdditionalCapability($joinClause);
		
		$whereClause = new WhereClause();
		$whereClause->addToClause(["CLIENT_ID", "=", $clientId]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause(["CLIENT_SECRET", "=", $clientSecret]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause(["ACCESS_TOKEN", "=", $userToken]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause(["ACCESS_SECRET", "=", $userSecret]);
		$query->addAdditionalCapability($whereClause);
		
		$query->execute();

		return !empty($query->getResult());
	}
}
