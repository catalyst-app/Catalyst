<?php

namespace Catalyst\API;

use \Catalyst\Secrets;
use \Exception;
use \InvalidArgumentException;

/**
 * Contains methods for handling data encrypted for transit
 */
class TransitEncryption {
	/**
	 * @var null|mixed
	 */
	static $private = null;

	/**
	 * Get the server's RSA private key in OpenSSL format
	 *
	 * @return mixed
	 */
	private static function getRsaPrivateKey() {
		if (!is_null(self::$private)) {
			return self::$private;
		}
		return self::$private = openssl_pkey_get_private(Secrets::getRsaPrivate());
	}

	/**
	 * Decrypts input using the system's private key
	 *
	 * @param string $in input, b64 or binary
	 * @param bool $b64 if the input is base 64 (true) or binary (false)
	 * @return string
	 * @throws InvalidArgumentException $in is not decodable using the system's key
	 */
	public static function decryptRsa(string $in, bool $b64 = true): string {
		$out = null;
		if ($b64) {
			$in = base64_decode($in);
			if ($in === false) {
				throw new InvalidArgumentException("Invalid base-64 encoding");
			}
		}
		if (!openssl_private_decrypt($in, $out, self::getRsaPrivateKey())) {
			throw new InvalidArgumentException("Server key could not decrypt the data");
		}
		$binary = hex2bin($out);
		if ($binary === false) {
			throw new Exception("Unable to use hex2bin on decrypted RSA");
		}
		return $binary;
	}

	/**
	 * Decrypts input JSON as produced with client encryptString function
	 *
	 * @param string $json input, b64 or binary
	 * @return string
	 * @throws InvalidArgumentException Key(s) is/are not decodable using the system's key
	 * @throws InvalidArgumentException JSON is invalid/not to spec
	 */
	public static function decryptAes(string $json): string {
		$json = json_decode($json, true);

		if (!is_array($json)) {
			throw new InvalidArgumentException("Invalid JSON");
		}

		foreach (["aesKey", "aesIv", "cipherText"] as $prop) {
			if (!array_key_exists($prop, $json) || !is_string($json[$prop])) {
				throw new InvalidArgumentException("Invalid JSON");
			}
		}

		$key = self::decryptRsa($json["aesKey"], true);
		$iv = self::decryptRsa($json["aesIv"], true);
		$ciphertext = hex2bin($json["cipherText"]);
		if ($ciphertext === false) {
			throw new Exception("Unable to use hex2bin on ciphertext");
		}

		$result = openssl_decrypt($ciphertext, "AES-256-CBC", $key, OPENSSL_RAW_DATA, $iv);
		if ($result === false) {
			throw new InvalidArgumentException("AES integrity failed");
		}

		return $result;
	}
}
