<?php

namespace Catalyst\Database;

use \Catalyst\Secrets;
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
	protected const DB_NAME = "CATALYST";
	protected const DB_USER = "CATALYST";
	// this is already public in Secrets so
	protected const DB_PASSWORD = Secrets::DB_PASSWORD;

	/**
	 * Contains the PDO instance for our database
	 * Should be accessed through ::getDbh so we can make sure its initialized
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
		self::$dbh = new PDO(DB_DRIVER.":host=".DB_SERVER.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASSWORD);
		if (DEVEL) {
			// show errors
			self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
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
