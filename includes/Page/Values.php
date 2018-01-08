<?php

namespace Redacted\Page;

class Values {
	// [keyword (for navbar), title]
	const HOME = ["home", "Home"];
	const LOGIN = ["login", "Login"];
	const LOGOUT = ["logout", "Logout"];
	const REGISTER = ["register", "Register"];
	const EMAIL_VERIFICATION = ["user", "Email Verification"];
	const DASHBOARD = ["user", "Dashboard | {name}"];
	const SETTINGS = ["user", "Settings | {name}"];
	const USER_PROFILE = ["null", "{name}"];
	const NEW_CHARACTER = ["user", "New Character"];
	const CHARACTERS = ["user", "My Characters"];
	const EDIT_CHARACTER = ["user", "Edit {name}"];
	const VIEW_CHARACTER = ["user", "{name}"];
	const MARKDOWN = ["about", "Markdown Help"];
	const EMOJI = ["about", "Emoji"];
	const VIEW_ARTIST = ["browse", "{name}"];
	const NEW_ARTIST_PAGE = ["artist", "New Artist"];
	const EDIT_ARTIST_PAGE = ["artist", "Edit Artist"];
	const EDIT_ARTIST_PAGE_COMMISSION_TYPES = ["artist", "Commission Types"];
	const NEW_COMMISSION_TYPE = ["artist", "New Commission Type"];
	const EDIT_COMMISSION_TYPE = ["artist", "Edit Commission Type"];
	const EDIT_COMMISSION_TYPE_IMAGES = ["artist", "Edit Commission Type Images"];

	const DEFAULT_COLOR = [
		"hex" => "1b5e20",
		"base" => "green",
		"mod" => "darken-4"
	];

	const ROOT_TITLE = "Redacted";

	const HEAD_INC = __DIR__."/Header/header.inc.php";
	const FOOTER_INC = __DIR__."/Footer/footer.inc.php";

	public static function createTitle(string $title, array $values=[]) : string {
		return preg_replace_callback("/{([^}]+)}/", function($in) use ($values) : string {
			return $values[$in[1]];
		}, $title);
	}
}
