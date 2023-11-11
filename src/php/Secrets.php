<?php

namespace Catalyst;

class Secrets {
	public static function get(string $key): string {
		// fetch from env
		if (isset($_ENV[$key])) {
			return $_ENV[$key];
		}
		throw new \LogicException("Unknown secret $key!");
	}

	public static function getRsaPublic(): string {
		return file_get_contents(REAL_ROOTDIR . "keys/key.pub");
	}

	public static function getRsaPrivate(): string {
		return file_get_contents(REAL_ROOTDIR . "keys/key.pem");
	}

}
