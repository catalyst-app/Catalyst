<?php

namespace Catalyst\Form;

class Captcha {
	public static function test(string $secret, string $captcha, string $ip=null) : bool {
		$ip = $ip ? $ip : $_SERVER["REMOTE_ADDR"];
		$opts = [
			"http" => [
				"method"  => "POST",
				"header"  => "Content-type: application/x-www-form-urlencoded",
				"content" => http_build_query([
					"secret" => $secret,
					"response" => $captcha,
					"remoteip" => $ip
				])
			],
			"ssl" => [
				"verify_peer" => false
			]
		];
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify", false, stream_context_create($opts));
		return json_decode($response)->success;
	}
}
