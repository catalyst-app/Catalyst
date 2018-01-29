<?php

namespace Catalyst\API;

/**
 * Utility functions for an endpoint
 */
class Endpoint {
	public static function checkAuthorizationHeaders() : bool {
		$headers = getallheaders();

		if (!array_key_exists("Client", $headers)) {
			\Catalyst\HTTPCode::set(401);
			\Catalyst\API\Response::sendErrorResponse(-1, "Client header was not passed.");
		}

		if (!array_key_exists("User", $headers)) {
			\Catalyst\HTTPCode::set(401);
			\Catalyst\API\Response::sendErrorResponse(-1, "User header was not passed.");
		}
	}
}