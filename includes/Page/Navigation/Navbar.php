<?php

namespace Catalyst\Page\Navigation;

class Navbar {
	public const NAME = 1;
	public const CALLABLE = 2;

	public const NORMAL_LINK = 1;
	public const DROPDOWN_PARENT = 2;
	public const DROPDOWN_CHILD = 3;
	public const DROPDOWN_DIVIDER = 4;
	public const PSUEDO_DROPDOWN_END = 5;

	public const NAVBAR = 0;
	public const SIDENAV = 1;

	private static function getItems() : array {
		// outermost key is access level
		// inner arr is: name|callable, name|callable flag, keyword, path, type, [flags]
		return [
			"all" => [
				["Home", self::NAME, "home", ROOTDIR, self::NORMAL_LINK],
				["Browse", self::NAME, "browse", ROOTDIR."Browse", self::NORMAL_LINK],
				["About", self::NAME, "about", ROOTDIR."About/", self::DROPDOWN_PARENT, "about-dropdown"],
				["About Us", self::NAME, null, ROOTDIR."About", self::DROPDOWN_CHILD],
				["Help Center", self::NAME, null, ROOTDIR."About", self::DROPDOWN_CHILD],
				["FAQ", self::NAME, null, ROOTDIR."About", self::DROPDOWN_CHILD],
				["Terms of Service", self::NAME, null, ROOTDIR."TOS", self::DROPDOWN_CHILD],
				[null, self::NAME, null, null, self::PSUEDO_DROPDOWN_END],
			],
			"not-artist" => [
				["Artist", self::NAME, "artist", ROOTDIR."Artist/".(\Catalyst\User\User::isLoggedIn() ? ($_SESSION["user"]->getArtistPage() ? $_SESSION["user"]->getArtistPage()->getURL() : "") : null), self::DROPDOWN_PARENT, "artist-dropdown"],
					["Create Page", self::NAME, null, ROOTDIR."Artist/New/", self::DROPDOWN_CHILD],
				[null, self::NAME, null, null, self::PSUEDO_DROPDOWN_END],
			],
			"artist" => [
				[[(\Catalyst\User\User::isLoggedIn() ? $_SESSION["user"]->getArtistPage() : null), "getNavbarDropdown"], self::CALLABLE, "artist", ROOTDIR."Artist/".(\Catalyst\User\User::isLoggedIn() ? ($_SESSION["user"]->getArtistPage() ? $_SESSION["user"]->getArtistPage()->getURL() : "") : null), self::DROPDOWN_PARENT, "artist-dropdown"],
				["My Page", self::NAME, null, ROOTDIR."Artist/".(\Catalyst\User\User::isLoggedIn() ? ($_SESSION["user"]->getArtistPage() ? $_SESSION["user"]->getArtistPage()->getURL() : "") : null), self::DROPDOWN_CHILD],
				["Edit Page", self::NAME, null, ROOTDIR."Artist/Edit", self::DROPDOWN_CHILD],
				["Commission Types", self::NAME, null, ROOTDIR."Artist/EditCommissionTypes", self::DROPDOWN_CHILD],
				[null, self::NAME, null, null, self::PSUEDO_DROPDOWN_END],
			],
			"nsfw" => [
				// 
			],
			"logged_in" => [
				[[(\Catalyst\User\User::isLoggedIn() ? $_SESSION["user"] : null), "getNavbarDropdown"], self::CALLABLE, "user", ROOTDIR."Dashboard", self::DROPDOWN_PARENT, "user-dropdown"],
					["Dashboard", self::NAME, null, ROOTDIR."Dashboard", self::DROPDOWN_CHILD],
					["Characters", self::NAME, null, ROOTDIR."Character", self::DROPDOWN_CHILD],
					["Settings", self::NAME, null, ROOTDIR."Settings", self::DROPDOWN_CHILD],
					[null, null, null, null, self::DROPDOWN_DIVIDER],
					["Logout", self::NAME, null, ROOTDIR."Logout", self::DROPDOWN_CHILD],
				[null, self::NAME, null, null, self::PSUEDO_DROPDOWN_END],
			],
			"logged_out" => [
				["Login", self::NAME, "login", ROOTDIR."Login", self::NORMAL_LINK],
				["Register", self::NAME, "register", ROOTDIR."Register", self::NORMAL_LINK],
			],
		];
	}

	public static function getNavbarItems(array $perms=["all"]) : array {
		$items = array_filter(self::getItems(), function($in) use ($perms) {
			return in_array($in, $perms);
		}, ARRAY_FILTER_USE_KEY);

		$items = array_merge(...array_values($items));

		return $items;
	}
}
