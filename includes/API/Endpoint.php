<?php

namespace Catalyst\API;

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

	/**
	 * Ran for every endpoint
	 */
	public static function init() {
		define("IS_API", true);
		self::checkAuthorizationHeaders();
	}

	/**
	 * Check if the authorization headers are valid
	 * 
	 * @return bool If the headers are valid
	 */
	public static function checkAuthorizationHeaders() : bool {
		$headers = getallheaders();

		if (!array_key_exists("Client", $headers)) {
			\Catalyst\HTTPCode::set(401);
			\Catalyst\API\Response::sendErrorResponse(10001, "Client header was not passed.");
			return false;
		}

		if (!array_key_exists("User", $headers)) {
			\Catalyst\HTTPCode::set(401);
			\Catalyst\API\Response::sendErrorResponse(10002, "User header was not passed.");
			return false;
		}

		$clientKeys = [];
		if (!preg_match(self::CLIENT_HEADER_REGEX, $headers["Client"], $clientKeys)) {
			\Catalyst\HTTPCode::set(401);
			\Catalyst\API\Response::sendErrorResponse(10003, "Client header is invalid.");
			return false;
		}

		$userKeys = [];
		if (!preg_match(self::USER_HEADER_REGEX, $headers["User"], $userKeys)) {
			\Catalyst\HTTPCode::set(401);
			\Catalyst\API\Response::sendErrorResponse(10004, "User header is invalid.");
			return false;
		}

		if (!self::checkClientKeys($clientKeys[1], $clientKeys[2])) {
			\Catalyst\HTTPCode::set(401);
			\Catalyst\API\Response::sendErrorResponse(10005, "Client does not exist.");
			return false;
		}

		if (!self::checkUserKeys($clientKeys[1], $clientKeys[2], $userKeys[1], $userKeys[2])) {
			\Catalyst\HTTPCode::set(401);
			\Catalyst\API\Response::sendErrorResponse(10006, "User tokens are invalid.");
			return false;
		}

		return true;
	}

	/**
	 * Checks if the client keys exist in the database and are valid
	 */
	public static function checkClientKeys(string $clientId, string $clientSecret) : bool {
		$query = new \Catalyst\Database\SelectQuery();
		$query->setTable(\Catalyst\Database\Tables::API_KEYS);
		$query->addColumn("ID");
		$whereClause = new \Catalyst\Database\WhereClause();
		$whereClause->addToClause(["CLIENT_ID", "=", $clientId]);
		$whereClause->addToClause(\Catalyst\Database\WhereClause::AND);
		$whereClause->addToClause(["CLIENT_SECRET", "=", $clientSecret]);
		$query->addAdditionalCapability($whereClause);
		$query->execute();
		var_dump($query->getResult());
	}
}