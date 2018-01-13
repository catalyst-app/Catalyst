<?php

namespace Catalyst;

class Tokens {
	// [a-z0-9]
	public const TOKEN_CHARS = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","0","1","2","3","4","5","6","7","8","9"];
	public const TOKEN_REGEX = '^[a-z0-9]*$';

	public const ARTIST_TOKEN_LENGTH = 32;
	public const CHARACTER_TOKEN_LENGTH = 50;
	public const COMMISSION_TOKEN_LENGTH = 100;
	public const COMMISSION_TYPE_TOKEN_LENGTH = 50;
	public const EMAIL_VERIFICATION_TOKEN_LENGTH = 12;
	public const PASSWORD_RESET_TOKEN_LENGTH = 16;
	public const USER_FILE_TOKEN_LENGTH = 32;

	public const ARTIST_TOKEN_REGEX = '^[a-z0-9]{'.self::ARTIST_TOKEN_LENGTH.'}$';
	public const CHARACTER_TOKEN_REGEX = '^[a-z0-9]{'.self::CHARACTER_TOKEN_LENGTH.'}$';
	public const COMMISSION_TOKEN_REGEX = '^[a-z0-9]{'.self::COMMISSION_TOKEN_LENGTH.'}$';
	public const COMMISSION_TYPE_TOKEN_REGEX = '^[a-z0-9]{'.self::COMMISSION_TYPE_TOKEN_LENGTH.'}$';
	public const EMAIL_VERIFICATION_TOKEN_REGEX = '^[a-z0-9]{'.self::EMAIL_VERIFICATION_TOKEN_LENGTH.'}$';
	public const PASSWORD_RESET_TOKEN_REGEX = '^[a-z0-9]{'.self::PASSWORD_RESET_TOKEN_LENGTH.'}$';
	public const USER_FILE_TOKEN_REGEX = '^[a-z0-9]{'.self::USER_FILE_TOKEN_LENGTH.'}$';

	public static function generateUniqueUserFileToken() : string {
		$token = self::generateUserFileToken();

		$tokenStmt = $GLOBALS["dbh"]->query("SELECT `FILE_TOKEN` FROM `".DB_TABLES["users"]."`;");
		$existingTokens = array_column($tokenStmt->fetchAll(), "FILE_TOKEN");
		$tokenStmt->closeCursor();

		while (in_array($token, $existingTokens)) {
			$token = self::generateUserFileToken();
		}

		return $token;
	}

	public static function generateUserFileToken() : string {
		return self::generateToken(self::USER_FILE_TOKEN_LENGTH);
	}

	public static function generateUniqueCharacterToken() : string {
		$token = self::generateCharacterToken();

		$tokenStmt = $GLOBALS["dbh"]->query("SELECT `CHARACTER_TOKEN` FROM `".DB_TABLES["characters"]."`;");
		$existingTokens = array_column($tokenStmt->fetchAll(), "CHARACTER_TOKEN");
		$tokenStmt->closeCursor();

		while (in_array($token, $existingTokens)) {
			$token = self::generateCharacterToken();
		}

		return $token;
	}

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

	public static function generatePasswordResetToken() : string {
		return self::generateToken(self::PASSWORD_RESET_TOKEN_LENGTH);
	}

	public static function generateToken(int $length) : string {
		$chars = self::TOKEN_CHARS;
		return str_shuffle(implode("", array_map(function ($in) use ($chars) { return $chars[array_rand($chars)]; }, range(1, $length))));
	}
}
