<?php

namespace Catalyst\User;

/**
 * Handle TOTP-related things
 */
class TOTP {
	// modified from https://github.com/Voronenko/PHPOTP/
	/**
	 * Generate a oath token from a key and counter
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
	 * @param string $hash String to truncate
	 * @return string Truncated hash (to 6 characters)
	 */
	public static function oathTruncate(string $hash) : string {
		$result=""; 

		$hashCharacters = str_split($hash,2);

		for ($j=0; $j<count($hashCharacters); $j++) {
			$hmacResult[]=hexdec($hashCharacters[$j]);
		}

		$offset = $hmacResult[19] & 0xf;

		$result = ((($hmacResult[$offset+0] & 0x7f) << 24 ) | (($hmacResult[$offset+1] & 0xff) << 16 ) | (($hmacResult[$offset+2] & 0xff) << 8 ) | ($hmacResult[$offset+3] & 0xff)) % pow(10,6);
		return $result;
	}
}
