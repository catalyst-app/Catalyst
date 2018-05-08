<?php

namespace Catalyst\User;

use \Catalyst\Tokens;
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
	 * @param string $token Token to check (string because of fucky int conversion)
	 * @param int $fuzz Number of 30s intervals to allow back/forward
	 */
	public static function checkToken(string $key, string $token, int $fuzz=9) : bool {
		$currentTime = time()/30;

		for($i=-$fuzz; $i<=$fuzz; $i++) {
			$checktime = (int)($currentTime+$i);
			$curKey = self::oathHotp($key, $checktime);

			if (((int)ltrim($token, 0)) == self::oathTruncate($curKey)) {
				return true;
			}
			
		}
		return false;
	}

	/**
	 * Generate a TOTP key
	 * 
	 * @return string A new TOTP key
	 */
	public static function generateKey() : string {
		$chars = ["2","3","4","5","6","7","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
		$key = Tokens::generateTokenFromCharset(16, $chars);
		
		$totpKey = "";

		$lut = ["A"=>0,"B"=>1,"C"=>2,"D"=>3,"E"=>4,"F"=>5,"G"=>6,"H"=>7,"I"=>8,"J"=>9,"K"=>10,"L"=>11,"M"=>12,"N"=>13,"O"=>14,"P"=>15,"Q"=>16,"R"=>17,"S"=>18,"T"=>19,"U"=>20,"V"=>21,"W"=>22,"X"=>23,"Y"=>24,"Z"=>25,"2"=>26,"3"=>27,"4"=>28,"5"=>29,"6"=>30,"7"=>31];

		$l = strlen($key);
		$n = 0;
		$j = 0;
		$totpKey = "";

		for ($i = 0; $i < $l; $i++) {
			$n = $n << 5;
			$n = $n + $lut[$key[$i]];
			$j = $j + 5;
			if ($j >= 8) {
				$j = $j - 8;
				$totpKey .= chr(($n & (0xFF << $j)) >> $j);
			}
		}

		return $totpKey;
	}

	/**
	 * Derive a human-readable 20-char key from the binary key
	 * 
	 * @param string $binaryKey Binary key
	 * @return string Human-readable key
	 */
	public static function getHumanKey(string $binaryKey) : string {
		$map = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','2','3','4','5','6','7','='];

		$binaryKey = str_split($binaryKey);
		$binaryString = "";
		$inputCount = count($binaryKey);
		for ($i = 0; $i < $inputCount; $i++) {
			$binaryString .= str_pad(base_convert(ord($binaryKey[$i]), 10, 2), 8, '0', STR_PAD_LEFT);
		}
		$fiveBitBinaryArray = str_split($binaryString, 5);
		$key = "";
		$i = 0;
		$fiveCount = count($fiveBitBinaryArray);
		while ($i < $fiveCount) {
			$key .= $map[base_convert(str_pad($fiveBitBinaryArray[$i], 5, '0'), 2, 10)];
			$i++;
		}

		return $key;
	}
}
