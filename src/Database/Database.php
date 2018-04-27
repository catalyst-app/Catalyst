<?php

namespace Catalyst\Database;

use \Catalyst\{Controller, Secrets};
use \PDO;

/**
 * Contains the database handler and DB connection info
 * 
 * Is only used for that, nothing else.  Constants are for connection info, public because may be used elsewhere
 */
class Database {
	protected const DB_DRIVER = "mysql";
	protected const DB_SERVER = "localhost";
	protected const DB_PORT = 3306;
	protected const DB_NAME = "catalyst";
	protected const DB_USER = "catalyst";
	// this is already public in Secrets so
	protected const DB_PASSWORD = Secrets::DB_PASSWORD;

	/**
	 * Contains the PDO instance for our database
	 * Should be accessed through ::getDbh so we can make sure its initialized
	 * 
	 * @var PDO
	 */
	protected static $dbh;

	/**
	 * Initializes database handler
	 */
	protected static function init() : void {
		// don't reinitialize
		if (self::$dbh instanceof PDO) {
			return;
		}
		self::$dbh = new PDO(self::DB_DRIVER.":host=".self::DB_SERVER.";port=".self::DB_PORT.";dbname=".self::DB_NAME.";charset=utf8mb4", self::DB_USER, self::DB_PASSWORD);
		// raise errors
		self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if (Controller::isDevelMode()) {
			self::$dbh->query("set profiling=1");
		}
	}

	/**
	 * Get the database handler
	 * 
	 * @return \PDO database handler
	 */
	public static function getDbh() : PDO {
		if (!(self::$dbh instanceof PDO)) {
			self::init();
		}
		return self::$dbh;
	}
}
