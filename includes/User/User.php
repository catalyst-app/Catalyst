<?php

namespace Catalyst\User;

use \Catalyst\Artist\Artist;
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Database\{Column, JoinClause, SelectQuery, Tables, WhereClause};
use \Catalyst\Email;
use \Catalyst\Images\{Folders, HasImageTrait, Image};
use \Catalyst\Integrations\HasSocialChipsTrait;
use \Catalyst\Message\MessagableTrait;
use \Catalyst\Page\Navigation\Navbar;
use \InvalidArgumentException;
use \Serializable;

/**
 * Represents a user
 */
class User implements Serializable {
	use HasImageTrait, HasSocialChipsTrait, MessagableTrait;

	/**
	 * The user's ID in the database
	 * @var int
	 */
	private $id;

	/**
	 * This is used as not to repeatedly hammer the database
	 * @var array
	 */
	private $cache = [];

	/**
	 * Create a new User object
	 * 
	 * @param int $id The User's ID (will be checked)
	 * @throws InvalidArgumentException on bad ID
	 */
	public function __construct(int $id) {
		if (!self::idExists($id)) {
			throw new InvalidArgumentException("User ID ".$id." does not exist in the database.");
		}

		$this->id = $id;
	}

	/**
	 * Returns the column's value from the database
	 * 
	 * @param string $column Column to get
	 * @return mixed
	 */
	public function getColumnFromDatabase(string $column) {
		$stmt = new SelectQuery();

		$stmt->setTable(Tables::USERS);
		$stmt->addColumn(new Column($column, Tables::USERS));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ID", Tables::USERS), "=", $this->getId()]);

		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		return $stmt->getResult()[0][$column];
	}

	/**
	 * Check if a given user ID exists in the database
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function idExists(int $id) : bool {
		$stmt = new SelectQuery();
		
		$stmt->setTable(Tables::USERS);
		$stmt->addColumn(new Column("ID", Tables::USERS));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ID", Tables::USERS), "=", $id]);

		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		if (count($stmt->getResult()) == 0) {
			return false;
		}
		return true;
	}

	/**
	 * Check if a user is logged in
	 * 
	 * @return bool
	 */
	public static function isLoggedIn() : bool {
		return array_key_exists("user",$_SESSION) && $_SESSION["user"] instanceof self;
	}

	/**
	 * Check if there is currently a 2FA authentication in progress
	 * 
	 * @return bool
	 */
	public static function isPending2FA() : bool {
		return array_key_exists("pending_user",$_SESSION) && $_SESSION["pending_user"] instanceof self;
	}

	/**
	 * Get the user's permission scope
	 * 
	 * As of writing, this consists of the following possible values:
	 * ["all","logged_out","logged_in","artist","not_artist","nsfw"]
	 * @return string[]
	 */
	public static function getPermissionScope() : array {
		if (self::isLoggedIn()) {
			$perms = ["all", "logged_in"];

			$currentUser = $_SESSION["user"];

			if ($currentUser->isArtist()) {
				$perms[] = "artist";
			}
			if (!$currentUser->isArtist()) {
				$perms[] = "not_artist";
			}
			if ($currentUser->isNsfw()) {
				$perms[] = "nsfw";
			}
			
			return $perms;
		} else {
			return ["all", "logged_out"];
		}
	}

	/**
	 * Get the User's ID
	 * 
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
	}

	/**
	 * Gets HTML for a message which designates a user-only page
	 * 
	 * @return string
	 */
	public static function getNotLoggedInHtml() : string {
		$str = '';

		$str .= '<div';
		$str .= ' class="section"';
		$str .= '>';

		$str .= '<p';
		$str .= ' class="flow-text"';
		$str .= '>';

		$str .= 'You must log in to access this page.  ';

		$str .= '<a';
		$str .= ' href="'.ROOTDIR.'Login"';
		$str .= '>';

		$str .= 'Login';

		$str .= '</a>';
		
		$str .= '</p>';

		$str .= '</div>';

		return $str;
	}

	/**
	 * Get the user's color
	 * 
	 * @return string 6-character hex code
	 */
	public function getColor() : string {
		if (array_key_exists("COLOR", $this->cache)) {
			return $this->cache["COLOR"];
		}

		return $this->cache["COLOR"] = bin2hex($this->getColumnFromDatabase("COLOR"));
	}

	/**
	 * Determine whether or not the User's email is verified
	 * 
	 * WILL ALSO return true if the user has no email set
	 * @return bool Whether the User's email address is verified
	 */
	public function emailIsVerified() : bool {
		if (array_key_exists("EMAIL_VERIFIED", $this->cache)) {
			return $this->cache["EMAIL_VERIFIED"];
		}

		if (is_null($this->getEmail())) {
			return $this->cache["EMAIL_VERIFIED"] = true;
		}

		$result = $this->cache["EMAIL_VERIFIED"] = (bool)($this->getColumnFromDatabase("EMAIL_VERIFIED"));

		return $result;
	}

	/**
	 * Get the user's TOTP key, or null if there is not one
	 * @return string|null
	 */
	public function getTotpKey() : ?string {
		if (array_key_exists("TOTP_KEY", $this->cache)) {
			return $this->cache["TOTP_KEY"];
		}

		return $this->cache["TOTP_KEY"] = $this->getColumnFromDatabase("TOTP_KEY");
	}

	/**
	 * Get whether or not the user has TOTP authentication enables
	 * 
	 * @return bool
	 */
	public function isTotpEnabled() : bool {
		return !is_null($this->getTotpKey());
	}

	/**
	 * Get a User's TOTP reset token, or null if none is set
	 * 
	 * The token will be null for users who have never had a TOTP token set.
	 * If they have used a token to reset their account, an admin should reset this
	 * @return null|string
	 */
	public function getTotpResetToken() : ?string {
		if (array_key_exists("TOTP_RESET_TOKEN", $this->cache)) {
			return $this->cache["TOTP_RESET_TOKEN"];
		}

		return $this->cache["TOTP_RESET_TOKEN"] = $this->getColumnFromDatabase("TOTP_RESET_TOKEN");
	}

	/**
	 * Get the User's email address, or null if none
	 * 
	 * @return string|null
	 */
	public function getEmail() : ?string {
		if (array_key_exists("EMAIL", $this->cache)) {
			return $this->cache["EMAIL"];
		}
		
		return $this->cache["EMAIL"] = $this->getColumnFromDatabase("EMAIL");
	}

	/**
	 * Get the token used to verify the User's e-mail address
	 * 
	 * Will change upon email address change
	 * @return string
	 */
	public function getEmailToken() : string {
		if (array_key_exists("EMAIL_TOKEN", $this->cache)) {
			return $this->cache["EMAIL_TOKEN"];
		}
		
		return $this->cache["EMAIL_TOKEN"] = $this->getColumnFromDatabase("EMAIL_TOKEN");
	}

	/**
	 * Get the User's nickname
	 * 
	 * @return string
	 */
	public function getNickname() : string {
		if (array_key_exists("NICK", $this->cache)) {
			return $this->cache["NICK"];
		}
		
		return $this->cache["NICK"] = $this->getColumnFromDatabase("NICK");
	}

	/**
	 * Get the user's username
	 * 
	 * @return string
	 */
	public function getUsername() : string {
		if (array_key_exists("USERNAME", $this->cache)) {
			return $this->cache["USERNAME"];
		}

		return $this->cache["USERNAME"] = $this->getColumnFromDatabase("USERNAME");
	}

	/**
	 * If the User is NSFW
	 * 
	 * l e w d
	 * also, shoutout to toooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooonyantooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooniooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo...
	 * ...ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo (he asked for 15k "o"s help me),
	 * he wanted to be shouted out in lewd
	 * @return bool 
	 */
	public function isNsfw() : bool {
		if (array_key_exists("NSFW", $this->cache)) {
			return $this->cache["NSFW"];
		}
		
		return $this->cache["NSFW"] = (bool)$this->getColumnFromDatabase("NSFW");
	}

	/**
	 * If the users profile picture is NSFW
	 * 
	 * @return bool
	 */
	public function isProfilePictureNsfw() : bool {
		if (array_key_exists("PICTURE_NSFW", $this->cache)) {
			return $this->cache["PICTURE_NSFW"];
		}
		
		return $this->cache["PICTURE_NSFW"] = (bool)$this->getColumnFromDatabase("PICTURE_NSFW");
	}

	/**
	 * Get the ID of the user's artist page, or null if they do not have one
	 * 
	 * @return int|null
	 */
	public function getArtistPageId() : ?int {
		if (array_key_exists("ARTIST_PAGE_ID", $this->cache)) {
			return $this->cache["ARTIST_PAGE_ID"];
		}
		
		return $this->cache["ARTIST_PAGE_ID"] = $this->getColumnFromDatabase("ARTIST_PAGE_ID");
	}

	/**
	 * Get the artist page associated with the user
	 * 
	 * @return null|Artist
	 */
	public function getArtistPage() : ?Artist {
		if (array_key_exists("ARTIST_PAGE", $this->cache)) {
			return $this->cache["ARTIST_PAGE"];
		}

		if (is_null($this->getArtistPageId())) {
			return null;
		} else {
			return $this->cache["ARTIST_PAGE"] = new Artist($this->getArtistPageId());
		}
	}

	/**
	 * Get the file token of the User
	 * 
	 * Used for storage of things like profile pictures
	 * 
	 * @return string
	 */
	public function getFileToken() : string {
		if (array_key_exists("FILE_TOKEN", $this->cache)) {
			return $this->cache["FILE_TOKEN"];
		}

		return $this->cache["FILE_TOKEN"] = $this->getColumnFromDatabase("FILE_TOKEN");
	}

	/**
	 * Get the User's profile photo
	 * 
	 * @return null|string The path, or null if there is none set (should default to default.png, per Images definition)
	 */
	public function getProfilePhoto() : ?string {
		if (array_key_exists("PROFILE_PHOTO", $this->cache)) {
			return $this->cache["PROFILE_PHOTO"];
		}

		return $this->cache["PROFILE_PHOTO"] = $this->getColumnFromDatabase("PICTURE_LOC");
	}

	/**
	 * Get the IDs of each item on the User's wishlist
	 * 
	 * @return int[]
	 */
	public function getWishlistIds() : array {
		if (array_key_exists("WISHLIST_IDS", $this->cache)) {
			return $this->cache["WISHLIST_IDS"];
		}
		
		$stmt = new SelectQuery();
		$stmt->setTable(Tables::USER_WISHLISTS);

		$stmt->addColumn(new Column("COMMISSION_TYPE_ID", Tables::USER_WISHLISTS));

		$joinClause = new JoinClause();
		$joinClause->setType(JoinClause::INNER);
		$joinClause->setJoinTable(Tables::COMMISSION_TYPES);
		$joinClause->setLeftColumn(new Column("ID", Tables::COMMISSION_TYPES));
		$joinClause->setRightColumn(new Column("COMMISSION_TYPE_ID", Tables::USER_WISHLISTS));
		$stmt->addAdditionalCapability($joinClause);

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("USER_ID", Tables::USER_WISHLISTS), "=", $this->getId()]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DELETED", Tables::COMMISSION_TYPES), "=", 0]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		return $this->cache["WISHLIST_IDS"] = array_column($stmt->getResult(), "COMMISSION_TYPE_ID");
	}

	/**
	 * Get all wishlist items as an array of CommissionType's
	 * 
	 * @return CommissionType[]
	 */
	public function getWishlistAsObjects() : array {
		if (array_key_exists("WISHLIST_OBJECTS", $this->cache)) {
			return $this->cache["WISHLIST_OBJECTS"];
		}

		$wishlistItems = [];

		foreach ($this->getWishlistIds() as $id) {
			$wishlistItems[] = new CommissionType($id);
		}

		return $this->cache["WISHLIST_OBJECTS"] = $wishlistItems;
	}

	/**
	 * Determine if a commission type ID is on the User's wishlist
	 * 
	 * @param int $id CommissionType ID
	 * @return bool
	 */
	public function idIsOnWishlist(int $id) : bool {
		return in_array($id, $this->getWishlistIds());
	}

	/**
	 * Determine if a CommissionType is on the User's wishlist
	 * 
	 * @param CommissionType $type
	 * @return bool
	 */
	public function isOnWishlist(CommissionType $type) : bool {
		return $this->idIsOnWishlist($type->getId());
	}

	/**
	 * Determine if the user is an artist
	 * 
	 * @return bool
	 */
	public function isArtist() : bool {
		return !is_null($this->getArtistPageId());
	}

	/**
	 * Get the HTML for the User's account in the navigation bar
	 * 
	 * @param int $bar The type of navbar
	 * @return string HTML
	 */
	public function getNavbarDropdown(int $bar) : string {
		if ($bar == Navbar::NAVBAR) {
			$str = "";
			$str .= $this->getImage()->getStrictCircleHtml(["valign"]); // valign needed to make it play nice
			$str .= htmlspecialchars($this->getNickname());
			return $str;
		} else { // sidebar
			return "My Account";
		}
	}

	/**
	 * Get the HTML for the sidenav header (pfp, username, nick)
	 * 
	 * @return HTML
	 */
	public function getSidenavHTML() : string {
		$str = "";

		$str .= '<li';
		$str .= ' class="center"';
		$str .= '>';

		$str .= $this->getImage()->getStrictCircleHtml();
		
		$str .= '<h5>';
		
		$str .= htmlspecialchars($this->getNickname());

		$str .= '</h5>';

		$str .= '<p';
		$str .= ' class="grey-text"';
		$str .= '>';

		$str .= htmlspecialchars($this->getUsername());
		
		$str .= '</p>';

		$str .= '</li>';

		return $str;
	}

	/**
	 * Remove a selected item from the internal cache
	 * 
	 * @param string|null $toClear the item to remove, or null for all
	 */
	public function clearCache(?string $toClear=null) : void {
		if (is_null($toClear)) {
			$this->cache = [];
		} else {
			unset($this->cache[$toClear]);
		}
	}

	/**
	 * Get the table used to store a User's social media items
	 * 
	 * @return string
	 */
	public function getSocialChipTable() : string {
		return Tables::USER_SOCIAL_MEDIA;
	}

	/**
	 * Get the column which is a foreign key to $this->getId()
	 * 
	 * @return string
	 */
	public function getSocialChipIdColumn() : string {
		return "USER_ID";
	}

	/**
	 * Get an ID if the username exists, and is unsuspended
	 * 
	 * @param string $username
	 * @return int -1 if not found
	 */
	public static function getIdFromUsername(string $username) : int {
		// check regex as not to sodomize the database
		if (!preg_match("/^([A-Za-z0-9._-]){2,64}$/", $username)) {
			return -1;
		}

		$stmt = new SelectQuery();

		$stmt->setTable(Tables::USERS);
		
		$stmt->addColumn(new Column("ID", Tables::USERS));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("USERNAME", Tables::USERS), "=", $username]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("SUSPENDED", Tables::USERS), "=", 0]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DEACTIVATED", Tables::USERS), "=", 0]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		if (empty($stmt->getResult())) {
			return -1;
		} else {
			$result = $stmt->getResult()[0]["ID"];
			return $result;
		}
	}

	public function sendVerificationEmail() : void {
		if ($this->emailIsVerified()) {
			return;
		}

		preg_match("/^(.*)(EmailVerification|Register|Settings|api).*/", \Catalyst\Page\UniversalFunctions::getRequestURI(), $out);
		$url = $out[1]."EmailVerification/?token=".$this->getEmailToken();

		\Catalyst\Email::sendEmail(
			[[$this->getEmail(), $this->getNickname()]],
			"Catalyst - Email verification",
			'<html><head><style>'.\Catalyst\Email::getCss($this->getColor()).'</style></head><body><div class="container"><div class="section"><h1 class="center header hide-on-small-only">Email Verification</h1><h3 class="center header hide-on-med-and-up">Email Verification</h3></div><div class="section"><p class="flow-text">Thank you for registering with Catalyst!</p><p class="flow-text">Please click the button below to activate your account.</p><div><a href="'.$url.'" class="btn">Verify</a></div><p>Alternatively, use the token <span style="font-weight: 700;">'.$this->getEmailToken().'</span> to verify your email.</p></div></div></body></html>',
			implode("\r\n", [
				"Email Verification",
				"",
				"Thank you for registering with Catalyst!",
				"Please go to the following URL to verify your account:",
				$url,
				"Alternatively, use the token ".$this->getEmailToken()
			]),
			Email::NO_REPLY_EMAIL,
			Email::NO_REPLY_PASSWORD
		);
	}

	public function initializeImage() : void {
		$this->setImage(new Image(Folders::PROFILE_PHOTO, $this->getFileToken(), $this->getProfilePhoto(), $this->isProfilePictureNsfw()));
	}

	public function serialize() : string {
		return $this->id;
	}

	public function unserialize($data) : void {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `FILE_TOKEN`,`USERNAME`,`EMAIL`,`EMAIL_VERIFIED`,`ARTIST_PAGE_ID`,`PICTURE_LOC`,`PICTURE_NSFW`,`NSFW`,`COLOR`,`NICK` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID AND  `DEACTIVATED` = 0 AND `SUSPENDED` = 0;");
		$stmt->bindParam(":ID", $data);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			throw new \InvalidArgumentException("The current user was suspended or deactivated.  Please refresh.");
		}

		$user = $stmt->fetchAll()[0];

		$stmt->closeCursor();

		$this->cache = [];$a = [
			"FILE_TOKEN" => $user["FILE_TOKEN"],
			"USERNAME" => $user["USERNAME"],
			"EMAIL" => $user["EMAIL"],
			"EMAIL_VERIFIED" => ((bool)($user["EMAIL_VERIFIED"]) || is_null($user["EMAIL"])),
			"ARTIST_PAGE_ID" => $user["ARTIST_PAGE_ID"],
			"PICTURE_LOC" => ($user["PICTURE_LOC"] ? $user["FILE_TOKEN"].$user["PICTURE_LOC"] : "default.png"),
			"PICTURE_NSFW" => (bool)($user["PICTURE_NSFW"]),
			"NSFW" => (bool)($user["NSFW"]),
			"COLOR" => bin2hex($user["COLOR"]),
			"NICK" => htmlspecialchars($user["NICK"])
		];

		$this->id = $data;
	}

	public function getMessageUrlPath() : string {
		return 'User/'.$this->getUsername();
	}

	public function getFriendlyName() : string {
		return $this->getNickname();
	}

	public static function isCurrentUserNsfw() : bool {
		if (!self::isLoggedIn()) {
			return false;
		}
		if (!$_SESSION["user"]->isNsfw()) {
			return false;
		}
		return true;
	}
}
