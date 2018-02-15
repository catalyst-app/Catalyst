<?php

namespace Catalyst;

use \Catalyst\Database\{Column, SelectQuery, Tables};

/**
 * A class which facilitates generation of (potentially unique) tokens
 */
class Tokens {
	// characters which nacan be made into tokens
	// [a-z0-9]
	public const TOKEN_CHARS = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9"];
	public const TOKEN_REGEX = '^[a-z0-9]*$';

	public const ARTIST_TOKEN_LENGTH = 32;
	public const CHARACTER_TOKEN_LENGTH = 50;
	public const COMMISSION_TOKEN_LENGTH = 100;
	public const COMMISSION_TYPE_TOKEN_LENGTH = 50;
	public const EMAIL_VERIFICATION_TOKEN_LENGTH = 12;
	public const PASSWORD_RESET_TOKEN_LENGTH = 16;
	public const TOTP_RESET_TOKEN_LENGTH = 20;
	public const USER_FILE_TOKEN_LENGTH = 32;

	public const ARTIST_TOKEN_REGEX = '^[a-z0-9]{'.self::ARTIST_TOKEN_LENGTH.'}$';
	public const CHARACTER_TOKEN_REGEX = '^[a-z0-9]{'.self::CHARACTER_TOKEN_LENGTH.'}$';
	public const COMMISSION_TOKEN_REGEX = '^[a-z0-9]{'.self::COMMISSION_TOKEN_LENGTH.'}$';
	public const COMMISSION_TYPE_TOKEN_REGEX = '^[a-z0-9]{'.self::COMMISSION_TYPE_TOKEN_LENGTH.'}$';
	public const EMAIL_VERIFICATION_TOKEN_REGEX = '^[a-z0-9]{'.self::EMAIL_VERIFICATION_TOKEN_LENGTH.'}$';
	public const PASSWORD_RESET_TOKEN_REGEX = '^[a-z0-9]{'.self::PASSWORD_RESET_TOKEN_LENGTH.'}$';
	public const TOTP_RESET_TOKEN_REGEX = '^[a-z0-9]{'.self::TOTP_RESET_TOKEN_LENGTH.'}$';
	public const USER_FILE_TOKEN_REGEX = '^[a-z0-9]{'.self::USER_FILE_TOKEN_LENGTH.'}$';

	/**
	 * Get a unique FILE_TOKEN for a User
	 * 
	 * @return string
	 */
	public static function generateUniqueUserFileToken() : string {
		$token = self::generateUserFileToken();

		$stmt = new SelectQuery();

		$stmt->setTable(Tables::USERS);
		$stmt->addColumn(new Column("FILE_TOKEN", Tables::USERS));

		$stmt->execute();

		$existingTokens = array_column($stmt->getResult(), "FILE_TOKEN");

		while (in_array($token, $existingTokens)) {
			$token = self::generateUserFileToken();
		}

		return $token;
	}

	/**
	 * Get a FILE_TOKEN for a User
	 * 
	 * @return string
	 */
	public static function generateUserFileToken() : string {
		return self::generateToken(self::USER_FILE_TOKEN_LENGTH);
	}

	/**
	 * Generate a unique CHARACTER_TOKEN for a Character
	 * 
	 * @return string
	 */
	public static function generateUniqueCharacterToken() : string {
		$token = self::generateCharacterToken();

		$stmt = new SelectQuery();

		$stmt->setTable(Tables::CHARACTERS);
		$stmt->addColumn(new Column("CHARACTER_TOKEN", Tables::CHARACTERS));

		$stmt->execute();

		$existingTokens = array_column($stmt->getResult(), "CHARACTER_TOKEN");

		while (in_array($token, $existingTokens)) {
			$token = self::generateCharacterToken();
		}

		return $token;
	}

	/**
	 * Generate a CHARACTER_TOKEN for a Character
	 * 
	 * @return string token
	 */
	public static function generateCharacterToken() : string {
		return self::generateToken(self::CHARACTER_TOKEN_LENGTH);
	}

	public static function generateUniqueCommissionTypeToken() : string {
		$token = self::generateCommissionTypeToken();

		$tokenStmt = $GLOBALS["dbh"]->query("SELECT `TOKEN` FROM `".DB_TABLES["commission_types"]."`;");
		$existingTokens = array_column($tokenStmt->fetchAll(), "TOKEN");
		$tokenStmt->closeCursor();

		while (in_array($token, $existingTokens)) {
			$token = self::generateCommissionTypeToken();
		}

		return $token;
	}

	public static function generateCommissionTypeToken() : string {
		return self::generateToken(self::COMMISSION_TYPE_TOKEN_LENGTH);
	}

	public static function generateUniqueArtistToken() : string {
		$token = self::generateArtistToken();

		$tokenStmt = $GLOBALS["dbh"]->query("SELECT `TOKEN` FROM `".DB_TABLES["artist_pages"]."`;");
		$existingTokens = array_column($tokenStmt->fetchAll(), "TOKEN");
		$tokenStmt->closeCursor();

		while (in_array($token, $existingTokens)) {
			$token = self::generateArtistToken();
		}

		return $token;
	}

	public static function generateArtistToken() : string {
		return self::generateToken(self::ARTIST_TOKEN_LENGTH);
	}

	public static function generateEmailVerificationToken() : string {
		return self::generateToken(self::EMAIL_VERIFICATION_TOKEN_LENGTH);
	}

	public static function generateTotpResetToken() : string {
		return self::generateToken(self::TOTP_RESET_TOKEN_LENGTH);
	}

	public static function generatePasswordResetToken() : string {
		return self::generateToken(self::PASSWORD_RESET_TOKEN_LENGTH);
	}

	/**
	 * Generate a psuedorandom token
	 * 
	 * This uses the Mersenne Twister algorithm (underlying for array_rand), which is secure enough
	 * and a psuedo-random enough number
	 * @param int $length
	 * @return string token
	 */
	public static function generateToken(int $length) : string {
		$chars = self::TOKEN_CHARS;
		return str_shuffle(implode("", array_map(function ($in) use ($chars) { return $chars[array_rand($chars)]; }, range(1, $length))));
	}

	/**
	 * Get an array of in-use tokens from the database
	 * 
	 * @param string $table Database table
	 * @param string $column Database column which holds tokens
	 * @return string[] Tokens currently in use
	 */
	public static function getTokensFromDatabase(string $table, string $column) : array {
		$stmt = new SelectQuery();

		$stmt->setTable($table);
		$stmt->addColumn(new Column($column, $table));

		$stmt->execute();

		return array_column($stmt->getResult(), $column);
	}

	/**
	 * Get a unique token with a given length and database column/table
	 * 
	 * @param int $length Token length
	 * @param string $table Table
	 * @param string $column Column
	 * @return string Unique token
	 */
	public static function generateUniqueToken(int $length, string $table, string $column) : string {
		$existingTokens = self::getTokensFromDatabase($column, $table);
		
		$token = self::generateToken($length);

		while (in_array($token, $existingTokens)) {
			$token = self::generateArtistToken();
		}

		return $token;
	}
}
