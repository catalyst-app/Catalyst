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
	 * Get a UNIQUE FILE_TOKEN for a User
	 * 
	 * @return string
	 */
	public static function generateUserFileToken() : string {
		return self::generateUniqueToken(self::USER_FILE_TOKEN_LENGTH, Tables::USERS, "FILE_TOKEN");
	}

	/**
	 * Generate a UNIQUE CHARACTER_TOKEN for a Character
	 * 
	 * @return string
	 */
	public static function generateCharacterToken() : string {
		return self::generateUniqueToken(self::CHARACTER_TOKEN_LENGTH, Tables::CHARACTERS, "CHARACTER_TOKEN");
	}

	/**
	 * Generate a UNIQUE TOKEN for a CommissionType
	 * 
	 * @return string token
	 */
	public static function generateCommissionTypeToken() : string {
		return self::generateUniqueToken(self::COMMISSION_TYPE_TOKEN_LENGTH, Tables::COMMISSION_TYPES, "TOKEN");
	}

	/**
	 * Generate a UNIQUE TOKEN for an Artist / artist page
	 * 
	 * @return string token
	 */
	public static function generateArtistToken() : string {
		return self::generateUniqueToken(self::ARTIST_TOKEN_LENGTH, Tables::ARTIST_PAGES, "TOKEN");
	}

	/**
	 * Generate a token for a user's email verification
	 * 
	 * @return string token
	 */
	public static function generateEmailVerificationToken() : string {
		return self::generateToken(self::EMAIL_VERIFICATION_TOKEN_LENGTH);
	}

	/**
	 * Generate a token used to reset TOTP authentication on a User's account
	 * 
	 * @return string token
	 */
	public static function generateTotpResetToken() : string {
		return self::generateToken(self::TOTP_RESET_TOKEN_LENGTH);
	}

	/**
	 * Generate a password reset token
	 * 
	 * See notes on generateToken for security-related concerns
	 * @return string token
	 */
	public static function generatePasswordResetToken() : string {
		return self::generateToken(self::PASSWORD_RESET_TOKEN_LENGTH);
	}

	/**
	 * Generate a psuedorandom token
	 * 
	 * This uses the Mersenne Twister algorithm (underlying for array_rand), which is secure enough
	 * and a psuedo-random enough number
	 * @param int $length
	 * @return string generated token
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
