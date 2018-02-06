<?php

namespace Catalyst\User;

use \InvalidArgumentException;

/**
 * Handle TOTP-related things
 */
class TOTP {
	/**
	 * Generate a oath token from a key and counter
	 * 
	 * modified from https://github.com/Voronenko/PHPOTP/
	 * 
	 * @param string $key Oath key to use to derive the hash
	 * @param int $counter Number of iterations since the key's birth to calculate
	 * @return string The token based on the key and counter
	 */
	public static function oathHotp(string $key, int $counter) : string { 
		$result = "";
		$curCounter = [0,0,0,0,0,0,0,0];

		for ($i=7;$i>=0;$i--) {
			$curCounter[$i] = pack ('C*', $counter); // C for unsigned char, * for  repeating to the end of the input data 
			$counter = $counter >> 8;
		}

		$binary = implode($curCounter);
		// Pad to 8 characters
		str_pad($binary, 8, chr(0), STR_PAD_LEFT);
		
		$result = hash_hmac('sha1', $binary, $key);

		return $result;
	}

	/**
	 * Truncate a hash to a given number of characters
	 * 
	 * modified from https://github.com/Voronenko/PHPOTP/
	 * 
	 * @param string $hash String to truncate
	 * @return int Truncated token from hash
	 */
	public static function oathTruncate(string $hash) : int {
		$result=""; 

		$hashCharacters = str_split($hash,2);

		$hmacResult = [];

		for ($j=0; $j<count($hashCharacters); $j++) {
			$hmacResult[]=hexdec($hashCharacters[$j]);
		}
		if (count($hmacResult) !== 20) {
			throw new InvalidArgumentException("Invalid hash passed to oathTruncate");
		}

		$offset = $hmacResult[19] & 0xf;

		$result = ((($hmacResult[$offset+0] & 0x7f) << 24 ) | (($hmacResult[$offset+1] & 0xff) << 16 ) | (($hmacResult[$offset+2] & 0xff) << 8 ) | ($hmacResult[$offset+3] & 0xff)) % pow(10,6);
		return $result;
	}

	/**
	 * Check a TOTP token
	 * 
	 * @param string $key TOTP key to check against
	 * @param int $token Token to check
	 * @param int $fuzz Number of 30s intervals to allow back/forward
	 */
	public static function checkToken(string $key, int $token, int $fuzz=2) : bool {
		$currentTime = time()/30;

		for($i=-$fuzz; $i<=$fuzz; $i++) {
			$checktime = (int)($currentTime+$i);
			$curKey = self::oathHotp($key, $checktime);
			
			if ($token == self::oathTruncate($curKey)) {
				return true;
			}
			
		}
		return false;
	}
}
