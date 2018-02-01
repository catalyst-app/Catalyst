<?php

namespace Catalyst\API;

use \Catalyst\Database\{Column, JoinClause, SelectQuery, Tables, WhereClause};
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

	static $isEndpoint = false;

	/**
	 * Ran for every endpoint
	 * 
	 * @param bool $internal Used to denote an internal API endpoint, affects authentication
	 * @param int $internalAuth Type of authentication to require for an internal endpoint, 1 = logged in, 2 = logged out, 0 = none
	 */
	public static function init(bool $internal=false, int $internalAuth=1) : void {
		self::$isEndpoint = true;
		if (!$internal) {
			self::checkAuthorizationHeaders();
		} else {
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
	public static function isApi() : bool {
		return self::$isEndpoint;
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
		$query = new SelectQuery();
		$query->setTable(Tables::API_KEYS);
		$query->addColumn(new Column("ID", Tables::API_KEYS));
		
		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("CLIENT_ID", Tables::API_KEYS), "=", $clientId]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("CLIENT_SECRET", Tables::API_KEYS), "=", $clientSecret]);
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
	protected static function checkUserKeys(string $clientId, string $clientSecret, string $userToken, string $userSecret) : bool {
		$query = new SelectQuery();
		$query->setTable(Tables::API_AUTHORIZATIONS);
		$query->addColumn(new Column("ID",Tables::API_KEYS));

		$joinClause = new JoinClause();
		$joinClause->setType(JoinClause::INNER);
		$joinClause->setJoinTable(Tables::API_KEYS);
		$joinClause->setLeftColumn(new Column("ID", Tables::API_KEYS));
		$joinClause->setRightColumn(new Column("API_ID", Tables::API_AUTHORIZATIONS));
		$query->addAdditionalCapability($joinClause);
		
		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("CLIENT_ID", Tables::API_KEYS), "=", $clientId]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("CLIENT_SECRET", Tables::API_KEYS), "=", $clientSecret]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("ACCESS_TOKEN", Tables::API_AUTHORIZATIONS), "=", $userToken]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("ACCESS_SECRET", Tables::API_AUTHORIZATIONS), "=", $userSecret]);
		$query->addAdditionalCapability($whereClause);
		
		$query->execute();

		return !empty($query->getResult());
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
		$query = new SelectQuery();
		$query->setTable(Tables::API_AUTHORIZATIONS);
		$query->addColumn(new Column("USER_ID", Tables::API_AUTHORIZATIONS));

		$joinClause = new JoinClause();
		$joinClause->setType(JoinClause::INNER);
		$joinClause->setJoinTable(Tables::API_KEYS);
		$joinClause->setLeftColumn(new Column("ID", Tables::API_KEYS));
		$joinClause->setRightColumn(new Column("API_ID", Tables::API_AUTHORIZATIONS));
		$query->addAdditionalCapability($joinClause);
		
		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("CLIENT_ID", Tables::API_KEYS), "=", $clientId]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("CLIENT_SECRET", Tables::API_KEYS), "=", $clientSecret]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("ACCESS_TOKEN", Tables::API_AUTHORIZATIONS), "=", $userToken]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("ACCESS_SECRET", Tables::API_AUTHORIZATIONS), "=", $userSecret]);
		$query->addAdditionalCapability($whereClause);
		
		$query->execute();

		$_SESSION["user"] = new User($query->getResult()[0]["USER_ID"]);
	}

	/**
	 * Check if there is a user logged in.  Used for internal API calls.
	 */
	protected static function checkLoggedIn() : void {
		if (!User::isLoggedIn()) {
			HTTPCode::set(401);
			Response::sendErrorResponse(99999, "User not logged in");
		}
	}


	/**
	 * Check if there is NOT a user logged in.  Used for internal API calls.
	 */
	protected static function checkLoggedOut() : void {
		if (User::isLoggedIn()) {
			HTTPCode::set(401);
			Response::sendErrorResponse(99998, "A user is logged in");
		}
	}
}
