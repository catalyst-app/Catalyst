<?php

namespace Catalyst\Page\Navigation;

use \Catalyst\Images\Image;
use \Catalyst\User\User;

/**
 * Holds constants and functions for navigation bar things
 */
class Navbar {
	// whether or not the item is a name, or should be called
	public const NAME = 0;
	public const CALLABLE = 1;

	// type of navbar item
	public const NORMAL_LINK = 1;
	public const DROPDOWN_PARENT = 2;
	public const DROPDOWN_CHILD = 3;
	public const DROPDOWN_DIVIDER = 4;
	public const PSUEDO_DROPDOWN_END = 5;

	// if the bar this item is a part of is sidebar or navbar, used for callable
	public const NAVBAR = 0;
	public const SIDENAV = 1;

	/**
	 * Get all possible navbar items
	 * 
	 * @return array[][]
	 */
	private static function getItems() : array {
		// don't repeatedly call
		$isLoggedIn = User::isLoggedIn();

		// outermost key is access level
		// inner arr is: name|callable, name|callable flag, keyword, path, type, [flags]
		/** @var array[array[string|callable,int,string|null,string,int[,string]]] */
		$items = [
			"all" => [
				// ["Home", self::NAME, "home", ROOTDIR, self::NORMAL_LINK],
				// ["Browse", self::NAME, "browse", ROOTDIR."Browse", self::NORMAL_LINK],
				["About", self::NAME, "about", ROOTDIR."About", self::DROPDOWN_PARENT, "about-dropdown"],
					["About Us", self::NAME, null, ROOTDIR."About", self::DROPDOWN_CHILD],
					// ["Blog", self::NAME, null, ROOTDIR."Blog", self::DROPDOWN_CHILD],
					// ["Help Center", self::NAME, null, ROOTDIR."Help", self::DROPDOWN_CHILD],
					// ["Feature Board", self::NAME, null, ROOTDIR."FeatureBoard", self::DROPDOWN_CHILD],
					// ["Developer Docs", self::NAME, null, ROOTDIR."Help/API", self::DROPDOWN_CHILD],
					["Terms of Service", self::NAME, null, ROOTDIR."Help/ToS", self::DROPDOWN_CHILD],
				[null, self::NAME, null, null, self::PSUEDO_DROPDOWN_END],
			],
			// "not_artist" => [
			// 	["Artist", self::NAME, "artist", ROOTDIR."Artist/".($isLoggedIn ? ($_SESSION["user"]->getArtistPage() ? $_SESSION["user"]->getArtistPage()->getUrl() : "") : null), self::DROPDOWN_PARENT, "artist-dropdown"],
			// 		["Create Page", self::NAME, null, ROOTDIR."Artist/New/", self::DROPDOWN_CHILD],
			// 	[null, self::NAME, null, null, self::PSUEDO_DROPDOWN_END],
			// ],
			// "artist" => [
			// 	[[($isLoggedIn ? $_SESSION["user"]->getArtistPage() : null), "getNavbarDropdown"], self::CALLABLE, "artist", ROOTDIR."Artist/".($isLoggedIn ? ($_SESSION["user"]->getArtistPage() ? $_SESSION["user"]->getArtistPage()->getUrl() : "") : null), self::DROPDOWN_PARENT, "artist-dropdown"],
			// 	["My Page", self::NAME, null, ROOTDIR."Artist/".($isLoggedIn ? ($_SESSION["user"]->getArtistPage() ? $_SESSION["user"]->getArtistPage()->getUrl() : "") : null), self::DROPDOWN_CHILD],
			// 	["Edit Page", self::NAME, null, ROOTDIR."Artist/Edit", self::DROPDOWN_CHILD],
			// 	["Commission Types", self::NAME, null, ROOTDIR."Artist/CommissionTypes", self::DROPDOWN_CHILD],
			// 	[null, self::NAME, null, null, self::PSUEDO_DROPDOWN_END],
			// ],
			// "nsfw" => [
			// 	// may be used for sfw toggle later
			// ],
			"logged_in" => [
				[[($isLoggedIn ? $_SESSION["user"] : null), "getNavbarDropdown"], self::CALLABLE, "user", ROOTDIR."Dashboard", self::DROPDOWN_PARENT, "user-dropdown"],
					["Dashboard", self::NAME, null, ROOTDIR."Dashboard", self::DROPDOWN_CHILD],
					["Characters", self::NAME, null, ROOTDIR."Character", self::DROPDOWN_CHILD],
					["Settings", self::NAME, null, ROOTDIR."Settings", self::DROPDOWN_CHILD],
					[null, null, null, null, self::DROPDOWN_DIVIDER],
					["Logout", self::NAME, null, ROOTDIR."Logout", self::DROPDOWN_CHILD],
				[null, self::NAME, null, null, self::PSUEDO_DROPDOWN_END],
			],
			"logged_out" => [
				["Login", self::NAME, "login", ROOTDIR."Login", self::NORMAL_LINK],
				// ["Register", self::NAME, "register", ROOTDIR."Register", self::NORMAL_LINK],
			],
		];

		return $items;
	}

	/**
	 * Get navbar items for the current state
	 * 
	 * @param string[] $perms Permissions to use to get navbar items
	 * @return array[][] Navbar items
	 */
	public static function getNavbarItems(array $perms=["all"]) : array {
		$items = array_filter(self::getItems(), function($in) use ($perms) {
			return in_array($in, $perms);
		}, ARRAY_FILTER_USE_KEY);

		$items = array_merge(...array_values($items));

		return $items;
	}

	/**
	 * Get the navbar item text/contents
	 * 
	 * @param int $bar Navbar type, see class constants
	 * @param array $navbarItem navbar item array, see spec in getNavbarItems
	 * @return string html-ready contents
	 */
	public static function getNavbarItemLabel(int $bar, array $navbarItem) : string {
		return $navbarItem[1] == self::CALLABLE ? call_user_func($navbarItem[0], $bar) : htmlspecialchars($navbarItem[0]);
	}

	/**
	 * Get the HTML to display thie logo HTML, in white
	 *
	 * @return string HTML to display logo in white
	 */
	public static function getLogoHtml() : string {
		return Image::getLogoImage()->getImgElementHtml();
	}
}
