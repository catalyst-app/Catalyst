<?php

namespace Catalyst\API;

use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\{JoinClause, WhereClause};
use \Catalyst\Database\Query\SelectQuery;
use \Catalyst\HTTPCode;
use \Catalyst\User\User;
use \InvalidArgumentException;

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
	 * If the currently accessed page is an endpoint
	 * 
	 * @var bool
	 */
	static $isEndpoint = false;
	/**
	 * If the current page is an internal endpoint
	 * 
	 * @var bool
	 */
	static $isInternalEndpoint = false;

	public const AUTH_REQUIRE_NONE = 0;
	public const AUTH_REQUIRE_LOGGED_IN = 1;
	public const AUTH_REQUIRE_LOGGED_OUT = 2;

	/**
	 * Ran for every endpoint
	 * 
	 * @param bool $internal Used to denote an internal API endpoint, affects authentication
	 * @param int $internalAuth Type of authentication to require for an internal endpoint
	 */
	public static function init(bool $internal=false, int $internalAuth=self::AUTH_REQUIRE_LOGGED_IN) : void {
		self::$isEndpoint = true;
		// ini_set('display_errors', 0); // don't pollute the JSON
		if (!$internal) {
			self::checkAuthorizationHeaders();
		} else {
			self::$isInternalEndpoint = true;
			switch ($internalAuth) {
				case 1:
					self::checkLoggedIn();
					break;
				case 2:
					self::checkLoggedOut();
					break;
				case 0: break; // none
				default:
					throw new InvalidArgumentException("Bad internal auth type specified");
			}
		}
	}

	/**
	 * Get if the current page is an API endpoint
	 * 
	 * @return bool if the current page is an API endpoint
	 */
	public static function isEndpoint() : bool {
		return self::$isEndpoint;
	}

	/**
	 * Get if the current page is an internal API endpoint
	 * 
	 * @return bool if the current page is an internal API endpoint
	 */
	public static function isInternalEndpoint() : bool {
		return self::$isInternalEndpoint;
	}

	/**
	 * Check if the authorization headers are valid
	 * 
	 * @return bool If the headers are valid
	 */
	protected static function checkAuthorizationHeaders() : bool {
		$headers = getallheaders();

		if (!array_key_exists("Client", $headers)) {
			HTTPCode::set(401);
			Response::sendErrorResponse(11001, ErrorCodes::ERR_11001);
			return false;
		}

		if (!array_key_exists("User", $headers)) {
			HTTPCode::set(401);
			Response::sendErrorResponse(11002, ErrorCodes::ERR_11002);
			return false;
		}

		$clientKeys = [];
		if (!preg_match(self::CLIENT_HEADER_REGEX, $headers["Client"], $clientKeys)) {
			HTTPCode::set(401);
			Response::sendErrorResponse(11003, ErrorCodes::ERR_11003);
			return false;
		}

		$userKeys = [];
		if (!preg_match(self::USER_HEADER_REGEX, $headers["User"], $userKeys)) {
			HTTPCode::set(401);
			Response::sendErrorResponse(11004, ErrorCodes::ERR_11004);
			return false;
		}

		if (!self::checkClientKeys($clientKeys[1], $clientKeys[2])) {
			HTTPCode::set(401);
			Response::sendErrorResponse(11005, ErrorCodes::ERR_11005);
			return false;
		}

		if (!self::checkUserKeys($clientKeys[1], $clientKeys[2], $userKeys[1], $userKeys[2])) {
			HTTPCode::set(401);
			Response::sendErrorResponse(11006, ErrorCodes::ERR_11006);
			return false;
		}
		
		self::loginWithKeys($clientKeys[1], $clientKeys[2], $userKeys[1], $userKeys[2]);
		
		return true;
	}

	/**
	 * Checks if the client keys exist in the database and are valid
	 * 
	 * @param string $clientId The app's client ID
	 * @param string $clientSecret The app's client secret
	 * @return bool if the keys are valid
	 */
	protected static function checkClientKeys(string $clientId, string $clientSecret) : bool {
		$stmt = new SelectQuery();
		$stmt->setTable(Tables::API_KEYS);
		$stmt->addColumn(new Column("ID", Tables::API_KEYS));
		
		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("CLIENT_ID", Tables::API_KEYS), "=", $clientId]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("CLIENT_SECRET", Tables::API_KEYS), "=", $clientSecret]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		return !empty($stmt->getResult());
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
	protected static function checkUserKeys(string $clientId, string $clientSecret, string $userToken, string $userSecret) : bool {
		$stmt = new SelectQuery();
		$stmt->setTable(Tables::API_AUTHORIZATIONS);
		$stmt->addColumn(new Column("ID",Tables::API_KEYS));

		$joinClause = new JoinClause();
		$joinClause->setType(JoinClause::INNER);
		$joinClause->setJoinTable(Tables::API_KEYS);
		$joinClause->setLeftColumn(new Column("ID", Tables::API_KEYS));
		$joinClause->setRightColumn(new Column("API_ID", Tables::API_AUTHORIZATIONS));
		$stmt->addAdditionalCapability($joinClause);
		
		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("CLIENT_ID", Tables::API_KEYS), "=", $clientId]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("CLIENT_SECRET", Tables::API_KEYS), "=", $clientSecret]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("ACCESS_TOKEN", Tables::API_AUTHORIZATIONS), "=", $userToken]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("ACCESS_SECRET", Tables::API_AUTHORIZATIONS), "=", $userSecret]);
		$stmt->addAdditionalCapability($whereClause);
		
		$stmt->execute();

		return !empty($stmt->getResult());
	}

	/**
	 * Logs in with the provided keys
	 * 
	 * @param string $clientId The app's client ID
	 * @param string $clientSecret The app's client secret
	 * @param string $userToken The user's token for the app
	 * @param string $userSecret The user's secret for the app
	 */
	protected static function loginWithKeys(string $clientId, string $clientSecret, string $userToken, string $userSecret) : void {
		$stmt = new SelectQuery();
		$stmt->setTable(Tables::API_AUTHORIZATIONS);
		$stmt->addColumn(new Column("USER_ID", Tables::API_AUTHORIZATIONS));

		$joinClause = new JoinClause();
		$joinClause->setType(JoinClause::INNER);
		$joinClause->setJoinTable(Tables::API_KEYS);
		$joinClause->setLeftColumn(new Column("ID", Tables::API_KEYS));
		$joinClause->setRightColumn(new Column("API_ID", Tables::API_AUTHORIZATIONS));
		$stmt->addAdditionalCapability($joinClause);
		
		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("CLIENT_ID", Tables::API_KEYS), "=", $clientId]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("CLIENT_SECRET", Tables::API_KEYS), "=", $clientSecret]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("ACCESS_TOKEN", Tables::API_AUTHORIZATIONS), "=", $userToken]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("ACCESS_SECRET", Tables::API_AUTHORIZATIONS), "=", $userSecret]);
		$stmt->addAdditionalCapability($whereClause);
		
		$stmt->execute();

		$_SESSION["user"] = new User($stmt->getResult()[0]["USER_ID"]);
	}

	/**
	 * Check if there is a user logged in.  Used for internal API calls.
	 */
	protected static function checkLoggedIn() : void {
		if (!User::isLoggedIn()) {
			HTTPCode::set(401);
			Response::sendErrorResponse(99901, ErrorCodes::ERR_99901);
		}
	}


	/**
	 * Check if there is NOT a user logged in.  Used for internal API calls.
	 */
	protected static function checkLoggedOut() : void {
		if (User::isLoggedIn()) {
			HTTPCode::set(401);
			Response::sendErrorResponse(99902, ErrorCodes::ERR_99902);
		}
	}
}
