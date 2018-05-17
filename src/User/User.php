<?php

namespace Catalyst\User;

use \Catalyst\Artist\Artist;
use \Catalyst\Character\Character;
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Database\{AbstractDatabaseModel, Column, Tables};
use \Catalyst\Database\QueryAddition\{JoinClause, WhereClause};
use \Catalyst\Database\Query\{DeleteQuery, InsertQuery, SelectQuery};
use \Catalyst\{Email, Tokens};
use \Catalyst\Images\{Folders, HasImageTrait, Image};
use \Catalyst\Integrations\HasSocialChipsTrait;
use \Catalyst\Message\MessagableTrait;
use \Catalyst\Page\Navigation\Navbar;
use \Catalyst\Page\{UniversalFunctions, Values};
use \InvalidArgumentException;
use \LogicException;

/**
 * Represents a user
 */
class User extends AbstractDatabaseModel {
	use HasImageTrait, HasSocialChipsTrait, MessagableTrait;

	/**
	 * Check if a user is logged in
	 * 
	 * @return bool
	 */
	public static function isLoggedIn() : bool {
		return isset($_SESSION) && array_key_exists("user",$_SESSION) && $_SESSION["user"] instanceof self;
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
	 * Verifies that the unserialized data is still usable
	 */
	protected function unserializeVerification() {
		if (self::getColumnFromDatabase("DELETED") || self::getColumnFromDatabase("SUSPENDED")) {
			throw new InvalidArgumentException("The current user was suspended or deactivated.  Please refresh.");
		}
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
	 * Whether or not the current user can see NSFW
	 * 
	 * @return bool false if sfw'd
	 */
	public static function isCurrentUserNsfw() : bool {
		if (!self::isLoggedIn()) {
			return false;
		}
		if (!$_SESSION["user"]->isNsfw()) {
			return false;
		}
		return true;
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

		$stmt->setTable(self::getTable());
		
		$stmt->addColumn(new Column("ID", self::getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("USERNAME", self::getTable()), "=", $username]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("SUSPENDED", self::getTable()), "=", 0]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DEACTIVATED", self::getTable()), "=", 0]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		if (empty($stmt->getResult())) {
			return -1;
		} else {
			$result = $stmt->getResult()[0]["ID"];
			return $result;
		}
	}

	/**
	 * From
	 * 
	 * @return string
	 */
	public static function getTable() : string {
		return Tables::USERS;
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
	 * If the User is NSFW
	 * 
	 * @return bool 
	 */
	public function isNsfw() : bool {
		return (bool)$this->getColumnFromDatabaseOrCache("NSFW");
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
	 * Get the user's color
	 * 
	 * @return string 6-character hex code
	 */
	public function getColor() : string {
		return bin2hex($this->getColumnFromDatabaseOrCache("NSFW"));
	}

	/**
	 * Verify the user's password
	 *
	 * @param string $password to test
	 * @return bool valid password
	 */
	public function verifyPassword(string $password) : bool {
		return password_verify($password, $this->getColumnFromDatabaseOrCache("HASHED_PASSWORD"));
	}

	/**
	 * Get the user's TOTP key, or null if there is not one
	 * @return string|null
	 */
	public function getTotpKey() : ?string {
		return $this->getColumnFromDatabaseOrCache("TOTP_KEY");
	}

	/**
	 * Get a User's TOTP reset token, or null if none is set
	 * 
	 * The token will be null for users who have never had a TOTP token set.
	 * If they have used a token to reset their account, an admin should reset this
	 * @return null|string
	 */
	public function getTotpResetToken() : ?string {
		return $this->getColumnFromDatabaseOrCache("TOTP_RESET_TOKEN");
	}

	/**
	 * Get the User's email address, or null if none
	 * 
	 * @return string|null
	 */
	public function getEmail() : ?string {
		return $this->getColumnFromDatabaseOrCache("EMAIL");
	}

	/**
	 * Determine whether or not the User's email is verified
	 * 
	 * WILL ALSO return true if the user has no email set
	 * @return bool Whether the User's email address is verified
	 */
	public function emailIsVerified() : bool {
		return is_null($this->getEmail()) ? true : $this->getColumnFromDatabaseOrCache("EMAIL_VERIFIED");
	}

	/**
	 * Get the token used to verify the User's e-mail address
	 * 
	 * Will change upon email address change
	 * @return string
	 */
	public function getEmailToken() : string {
		return $this->getColumnFromDatabaseOrCache("EMAIL_TOKEN");
	}

	/**
	 * Send the User a verification e-mail, if their email is not yet verified
	 */
	public function sendVerificationEmail() : void {
		if ($this->emailIsVerified() || is_null($this->getEmail())) { // is_null is really for phpstan, as emailIsVerified has that within
			return;
		}

		// get the base URL to use
		// maybe change this to a hardcoded catalystapp.co ?
		$out = [];
		if (!preg_match("/^(.*)(EmailVerification|Register|Settings|api).*/", UniversalFunctions::getRequestUrl(), $out)) {
			throw new LogicException("User::sendVerificationEmail called from an unknown page");
		}
		$url = $out[1]."EmailVerification/?token=".$this->getEmailToken();

		$subject = "Catalyst - Email verification";

		$htmlEmail = "";

		$htmlEmail .= Email::getEmailHeadHtml($this->getColor());
		
		$htmlEmail .= '<div';
		$htmlEmail .= ' class="container"';
		$htmlEmail .= '>';

		$htmlEmail .= UniversalFunctions::createHeading("Email Verification");

		$htmlEmail .= '<div';
		$htmlEmail .= ' class="section">';

		$htmlEmail .= '<p';
		$htmlEmail .= ' class="flow-text"';
		$htmlEmail .= '>';

		$htmlEmail .= 'Thank you for registering with Catalyst!';
		
		$htmlEmail .= '</p>';
		
		$htmlEmail .= '<p';
		$htmlEmail .= ' class="flow-text">';
		
		$htmlEmail .= 'Please click the button below to activate your account.';
		
		$htmlEmail .= '</p>';
		
		$htmlEmail .= '<div>'; // wrapping in a block
		
		$htmlEmail .= '<a';
		$htmlEmail .= ' href="'.$url.'"';
		$htmlEmail .= ' class="btn"';
		$htmlEmail .= '>';
		
		$htmlEmail .= 'Verify';
		
		$htmlEmail .= '</a>';
		
		$htmlEmail .= '</div>';

		$htmlEmail .= '<p>';

		
		$htmlEmail .= 'Alternatively, use the token ';
		
		$htmlEmail .= '<span';
		$htmlEmail .= ' style="';
		$htmlEmail .= 'font-weight: 700;';
		$htmlEmail .= 'font-family: monospace;';
		$htmlEmail .= '"'; // bold
		$htmlEmail .= '>';
		
		$htmlEmail .= ''.$this->getEmailToken().'';
		
		$htmlEmail .= '</span>';
		
		$htmlEmail .= ' to verify your email.';
		
		$htmlEmail .= '</p>';
		
		$htmlEmail .= '</div>';
		
		$htmlEmail .= '</div>';
		
		$htmlEmail .= '</body>';
		
		$htmlEmail .= '</html>';


		$textEmail = '';
		$textEmail .= 'Email Verification'."\r\n";
		$textEmail .= "\r\n";
		$textEmail .= 'Thank you for registering with Catalyst!'."\r\n";
		$textEmail .= 'Please go to the following URL to verify your account:'."\r\n";
		$textEmail .= $url."\r\n";
		$textEmail .= "Alternatively, use the token ".$this->getEmailToken().' to verify your email'."\r\n";

		
		Email::sendEmail([[$this->getEmail(), $this->getNickname()]], $subject, $htmlEmail, $textEmail, Email::NO_REPLY_EMAIL, Email::NO_REPLY_PASSWORD);
	}

	/**
	 * Get the User's nickname
	 * 
	 * @return string
	 */
	public function getNickname() : string {
		return $this->getColumnFromDatabaseOrCache("NICK");
	}

	/**
	 * Get the user's username
	 * 
	 * @return string
	 */
	public function getUsername() : string {
		return $this->getColumnFromDatabaseOrCache("USERNAME");
	}

	/**
	 * Get the file token of the User
	 * 
	 * Used for storage of things like profile pictures
	 * 
	 * @return string
	 */
	public function getFileToken() : string {
		return $this->getColumnFromDatabaseOrCache("FILE_TOKEN");
	}

	/**
	 * Get the ID of the user's artist page, or null if they do not have one
	 * 
	 * @return int|null
	 */
	public function getArtistPageId() : ?int {
		return $this->getColumnFromDatabaseOrCache("ARTIST_PAGE_ID");
	}

	/**
	 * If the user was an artist, but hid their page
	 * 
	 * @return bool
	 */
	public function wasArtist() : bool {
		return $this->getDataFromCallableOrCache("WAS_ARTIST", function() : bool {
			$stmt = new SelectQuery();

			$stmt->setTable(Artist::getTable());

			$stmt->addColumn(new Column("ID", Artist::getTable()));

			$whereClause = new WhereClause();
			$whereClause->addToClause([new Column("USER_ID", Artist::getTable()), '=', $this->getId()]);
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("DELETED", Artist::getTable()), '=', 1]);
			$stmt->addAdditionalCapability($whereClause);

			$stmt->execute();

			return count($stmt->getResult()) == 1;
		});
	}

	/**
	 * Get the artist page associated with the user
	 * 
	 * @return null|Artist
	 */
	public function getArtistPage() : ?Artist {
		return $this->getDataFromCallableOrCache("ARTIST_PAGE_OBJ", function() : ?Artist {
			if (is_null($this->getArtistPageId())) {
				return null;
			} else {
				return new Artist($this->getArtistPageId());
			}
		});
	}

	/**
	 * Get the IDs of each item on the User's wishlist
	 * 
	 * @return int[]
	 */
	public function getWishlistIds() : array {
		return $this->getDataFromCallableOrCache("WISHLIST_IDS", function() : array {
			$stmt = new SelectQuery();

			$stmt->setTable(Tables::USER_WISHLISTS);

			$stmt->addColumn(new Column("COMMISSION_TYPE_ID", Tables::USER_WISHLISTS));

			$joinClause = new JoinClause();
			$joinClause->setType(JoinClause::INNER);
			$joinClause->setJoinTable(CommissionType::getTable());
			$joinClause->setLeftColumn(new Column("ID", CommissionType::getTable()));
			$joinClause->setRightColumn(new Column("COMMISSION_TYPE_ID", Tables::USER_WISHLISTS));
			$stmt->addAdditionalCapability($joinClause);

			$whereClause = new WhereClause();
			$whereClause->addToClause([new Column("USER_ID", Tables::USER_WISHLISTS), "=", $this->getId()]);
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("DELETED", CommissionType::getTable()), "=", 0]);
			$stmt->addAdditionalCapability($whereClause);

			$stmt->execute();

			return array_column($stmt->getResult(), "COMMISSION_TYPE_ID");
		});
	}

	/**
	 * Get all wishlist items as an array of CommissionType's
	 * 
	 * @return CommissionType[]
	 */
	public function getWishlistAsObjects() : array {
		return $this->getDataFromCallableOrCache("WISHLIST_OBJS", function() : array {
			return array_map(function(int $id) : CommissionType {
				return new CommissionType($id);
			}, $this->getWishlistIds());
		});
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
	 * Straight out of the HasImageTrait
	 */
	public function initializeImage() : void {
		$this->setImage(new Image(Folders::PROFILE_PHOTO, $this->getFileToken(), $this->getColumnFromDatabaseOrCache("PICTURE_LOC"), $this->getColumnFromDatabaseOrCache("PICTURE_NSFW")));
	}

	/**
	 * Part of IsMessagableTrait
	 * 
	 * @return string URL, relative to ROOTDIR/Message/New/, that can be used for messaging
	 */
	public function getMessageUrlPath() : string {
		return 'User/'.$this->getUsername();
	}

	/**
	 * Get a friendly name for the object, part of IsMessagableTrait
	 * 
	 * @return string The User's nickname
	 */
	public function getFriendlyName() : string {
		return $this->getNickname();
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
	 * @return string HTML
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
	 * Get deleted values for when a user is delet
	 * @return array
	 */
	public static function getDeletedValues() : array {
		return [
			// "FILE_TOKEN" => "", omitted
			// "USERNAME" => "" omitted
			"HASHED_PASSWORD" => "DELETED USER",
			"PASSWORD_RESET_TOKEN" => Tokens::generatePasswordResetToken(),
			"TOTP_KEY" => null,
			"TOTP_RESET_TOKEN" => Tokens::generateTotpResetToken(),
			"EMAIL_TOKEN" => Tokens::generateEmailVerificationToken(),
			"ARTIST_PAGE_ID" => null,
			"PICTURE_LOC" => null,
			"PICTURE_NSFW" => 0,
			"COLOR" => hex2bin(Values::DEFAULT_COLOR),
			"NICK" => "Deleted user",
			"DEACTIVATED" => 1,
		];
	}

	/**
	 * Overridden to do additional deletion of subitems
	 *
	 * @todo archive commissions
	 * @todo feature board thingies
	 * @todo wishlist
	 */
	public function additionalDeletion() : void {
		$removeApiAuthorizationsQuery = new DeleteQuery();
		$removeApiAuthorizationsQuery->setTable(Tables::API_AUTHORIZATIONS);
		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("USER_ID", Tables::API_AUTHORIZATIONS), "=", $this->getId()]);
		$removeApiAuthorizationsQuery->addAdditionalCapability($whereClause);
		$removeApiAuthorizationsQuery->execute();

		$removeApiKeysQuery = new DeleteQuery();
		$removeApiKeysQuery->setTable(Tables::API_KEYS);
		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("USER_ID", Tables::API_KEYS), "=", $this->getId()]);
		$removeApiKeysQuery->addAdditionalCapability($whereClause);
		$removeApiKeysQuery->execute();

		if (!is_null($this->getArtistPage())) { // was artist will have already been deleted
			$this->getArtistPage()->delete();
		}

		foreach(Character::getCharactersFromUser($this) as $character) {
			$character->delete();
		}

		$this->deleteSocialChipsFromDatabase();
	}

	/**
	 * Create a user
	 *
	 * @param array $values
	 * @return self
	 */
	public static function create(array $values) : User {
		// per array_merge docs:
		// If the input arrays have the same string keys, then the latter value
		//  for that key will overwrite the previous one
		$values = array_merge([
			"FILE_TOKEN" => Tokens::generateUserFileToken(),
			"PASSWORD_RESET_TOKEN" => Tokens::generatePasswordResetToken(),
			"TOTP_KEY" => null,
			"TOTP_RESET_TOKEN" => Tokens::generateTotpResetToken(),
			"EMAIL" => null,
			"EMAIL_VERIFIED" => 0,
			"EMAIL_TOKEN" => Tokens::generateEmailVerificationToken(),
			"ARTIST_PAGE_ID" => null,
			"PICTURE_LOC" => null,
			"PICTURE_NSFW" => 0,
			"NSFW" => 0,
			"NICK" => $values["USERNAME"],
			"COLOR" => hex2bin(Values::DEFAULT_COLOR),
			"REFERRER" => null,
		], $values);

		$stmt = new InsertQuery();

		$stmt->setTable(self::getTable());

		foreach (["FILE_TOKEN", "USERNAME", "HASHED_PASSWORD", "PASSWORD_RESET_TOKEN", "EMAIL", "EMAIL_TOKEN", "PICTURE_LOC", "PICTURE_NSFW", "NSFW", "COLOR", "NICK", "REFERRER"] as $column) {
			$stmt->addColumn(new Column($column, self::getTable()));
			$stmt->addValue($values[$column]);
		}

		$stmt->execute();

		$user = new self($stmt->getResult());

		// if the user's email is null, this will silently return
		$user->sendVerificationEmail();

		return $user;
	}
}
