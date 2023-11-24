<?php

namespace Catalyst;

class Secrets {
	public static function get(string $key): string {
		// fetch from env
		if (isset($key)) {
			return getenv($key);
		}
		throw new \LogicException("Unknown secret $key!");
	}

	public static function isset(string $key): bool {
		return getenv($key) !== false && getenv($key) !== "";
	}

	public static function getRsaPublic(): string {
		return file_get_contents(REAL_ROOTDIR . "keys/key.pub");
	}

	public static function getRsaPrivate(): string {
		return file_get_contents(REAL_ROOTDIR . "keys/key.pem");
	}

}
