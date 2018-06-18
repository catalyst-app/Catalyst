<?php

namespace Catalyst\User;

use \Catalyst\Artist\Artist;
use \Catalyst\Character\Character;
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Database\Query\{DeleteQuery, InsertQuery, SelectQuery};
use \Catalyst\Database\QueryAddition\{JoinClause, WhereClause};
use \Catalyst\Database\{AbstractDatabaseModel, Column, Tables};
use \Catalyst\Email\Email;
use \Catalyst\Images\{Folders, HasImageTrait, Image};
use \Catalyst\Tokens;
use \Catalyst\Integrations\HasSocialChipsTrait;
use \Catalyst\Message\MessagableTrait;
use \Catalyst\Page\Navigation\Navbar;
use \Catalyst\Page\{UniversalFunctions, Values};
use \InvalidArgumentException;
use \LogicException;

/**
 * Represents a user
 *
 * @method bool isApprovedBetaTester()
 * @method void setApprovedBetaTester(bool $approvedBetaTester)
 * @method string getToken()
 * @method void setToken(string $token)
 * @method bool isNsfw()
 * @method void setNsfw(bool $nsfw)
 * @method string getNickname()
 * @method void setNickname(string $nickname)
 * @method string getUsername()
 * @method void setUsername(string $username)
 * @method null|int getArtistPageId()
 * @method void setArtistPageId(null|int $artistPageId)
 * @method null|string getTotpKey()
 * @method void setTotpKey(null|string $totpKey)
 * @method null|string getTotpResetToken()
 * @method void setTotpResetToken(null|string $totpResetToken)
 * @method null|string getEmail()
 * @method void setEmail(null|string $email)
 * @method bool isEmailVerified()
 * @method void setEmailVerified(bool $emailVerified)
 * @method bool isEmailVerificationSendable()
 * @method void setEmailVerificationSendable(bool $sendable)
 * @method string getColor()
 * @method void setColor(string $color)
 * @method bool isSuspended()
 * @method void setSuspended(bool $suspended)
 * @method bool isDeactivated()
 * @method void setDeactivated(bool $deactivated)
 * @method string getProfilePicturePath()
 * @method void setProfilePicturePath(string $path)
 * @method bool isProfilePictureNsfw()
 * @method void setProfilePictureNsfw(bool $nsfw)
 * @method string getPasswordResetToken()
 * @method void setPasswordResetToken(string $token)
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
		if ($this->isDeactivated() || $this->isSuspended()) {
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
	public static function getIdFromUsername(string $username, bool $allowSuspendedAndDeactivated=false) : int {
		// check regex as not to sodomize the database
		if (!preg_match("/^([A-Za-z0-9._-]){2,64}$/", $username)) {
			return -1;
		}

		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());
		
		$stmt->addColumn(new Column("ID", self::getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("USERNAME", self::getTable()), "=", $username]);
		if (!$allowSuspendedAndDeactivated) {
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("SUSPENDED", self::getTable()), "=", 0]);
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("DEACTIVATED", self::getTable()), "=", 0]);
		}
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		if (empty($stmt->getResult())) {
			return -1;
		} else {
			return $stmt->getResult()[0]["ID"];
		}
	}

	/**
	 * Get an ID if the username exists, and is unsuspended
	 * 
	 * @param string $email
	 * @return int -1 if not found
	 */
	public static function getIdFromEmail(string $email, bool $allowSuspendedAndDeactivated=false) : int {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());
		
		$stmt->addColumn(new Column("ID", self::getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("EMAIL", self::getTable()), "=", $email]);
		if (!$allowSuspendedAndDeactivated) {
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("SUSPENDED", self::getTable()), "=", 0]);
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("DEACTIVATED", self::getTable()), "=", 0]);
		}
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		if (empty($stmt->getResult())) {
			return -1;
		} else {
			return $stmt->getResult()[0]["ID"];
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
	 * The folder containing the image
	 * @return string
	 */
	public static function getImageFolder() : string {
		return Folders::PROFILE_PHOTO;
	}

	/**
	 * Verify the user's password
	 *
	 * @param string $password to test
	 * @return bool valid password
	 */
	public function verifyPassword(string $password) : bool {
		$valid = password_verify($password, $this->getColumnFromDatabaseOrCache("HASHED_PASSWORD"));
		if ($valid && password_needs_rehash($this->getColumnFromDatabaseOrCache("HASHED_PASSWORD"), PASSWORD_BCRYPT, ["cost" => Values::BCRYPT_COST])) {
			$this->setPassword($password);
		}
		return $valid;
	}

	/**
	 * Create a password hash
	 *
	 * @param string $password to test
	 * @return string new password
	 */
	public static function hashPassword(string $password) : string {
		return password_hash($password, PASSWORD_BCRYPT, ["cost" => Values::BCRYPT_COST]);
	}

	/**
	 * @param string $password to set
	 */
	public function setPassword(string $password) : void {
		$this->updateColumnInDatabase("HASHED_PASSWORD", self::hashPassword($password));
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
		if ($this->isEmailVerified() || is_null($this->getEmail())) { // is_null is really for phpstan, as isEmailVerified has that within
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

		
		$success = Email::sendEmail([[$this->getEmail(), $this->getNickname()]], $subject, $htmlEmail, $textEmail, Email::NO_REPLY_EMAIL, Email::NO_REPLY_PASSWORD);

		$this->setEmailVerificationSendable($success);
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
	 * Get all wishlist items as an array of WishlistItem's
	 * 
	 * @return WishlistItem[]
	 */
	public function getWishlist() : array {
		return $this->getDataFromCallableOrCache("WISHLIST_OBJS", function() : array {
			return WishlistItem::getUserWishlist($this);
		});
	}

	/**
	 * Get all wishlist items as an array of CT IDSs
	 * 
	 * @return int[]
	 */
	public function getWishlistCommissionTypeIds() : array {
		return $this->getDataFromCallableOrCache("WISHLIST_CT_IDS", function() : array {
			$result = [];
			foreach ($this->getWishlist() as $wishlistItem) {
				$result[] = $wishlistItem->getCommissionTypeId();
			}
			return $result;
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
		$this->setImage(new Image(self::getImageFolder(), $this->getToken(), $this->getColumnFromDatabaseOrCache("PICTURE_LOC"), $this->getColumnFromDatabaseOrCache("PICTURE_NSFW")));
	}

	/**
	 * Part of IsMessagableTrait
	 * 
	 * @return string URL, relative to ROOTDIR/Message/New/, that can be used for messaging
	 */
	public function getMessageUrlPath() : string {
		return 'User/'.$this->getUsername().'/';
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
	public function getDeletedValues() : array {
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
			"PICTURE_NSFW" => false,
			"COLOR" => hex2bin(Values::DEFAULT_COLOR),
			"NICK" => "Deleted user",
			"DEACTIVATED" => true,
		];
	}

	/**
	 * @return array
	 */
	public static function getPrefetchColumns() : array {
		return [
			"FILE_TOKEN",
			"USERNAME",
			"APPROVED_BETA_TESTER",
			"HASHED_PASSWORD",
			"PASSWORD_RESET_TOKEN",
			"TOTP_KEY",
			"TOTP_RESET_TOKEN",
			"EMAIL",
			"EMAIL_VERIFIED",
			"EMAIL_TOKEN",
			"ARTIST_PAGE_ID",
			"PICTURE_LOC",
			"PICTURE_NSFW",
			"NSFW",
			"COLOR",
			"NICK",
			"SUSPENDED",
			"DEACTIVATED",
		];
	}

	/**
	 * Overridden to do additional deletion of subitems
	 *
	 * @todo archive commissions
	 * @todo feature board thingies
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

		foreach(WishlistItem::getUserWishlist($this) as $wishlistItem) {
			$wishlistItem->delete();
		}
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

		$user = new self($stmt->getResult(), $values);

		// if the user's email is null, this will silently return
		$user->sendVerificationEmail();

		return $user;
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
	 * Get whether or not the user has TOTP authentication enables
	 * 
	 * @return bool
	 */
	public function isTotpEnabled() : bool {
		return !is_null($this->getTotpKey());
	}

	/**
	 * Get modifiable properties for the model
	 *
	 * 	"Name" => ["COLUMN_NAME", function($value) {return $out;}, function($newValue) {return $out;}]
	 * @return array
	 */
	public static function getModifiableProperties() : array {
		return [
			"ApprovedBetaTester" => ["APPROVED_BETA_TESTER", "boolval", null],
			"Token" => ["FILE_TOKEN", null, null],
			"Nsfw" => ["NSFW", "boolval", null],
			"Nickname" => ["NICK", null, null],
			"Username" => ["USERNAME", null, null],
			"ArtistPageId" => ["ARTIST_PAGE_ID", null, null],
			"TotpKey" => ["TOTP_KEY", null, null],
			"TotpResetToken" => ["TOTP_RESET_TOKEN", null, null],
			"Email" => ["EMAIL", null, null],
			"EmailToken" => ["EMAIL_TOKEN", null, null],
			"EmailVerified" => ["EMAIL_VERIFIED", "boolval", null],
			"EmailVerificationSendable" => ["EMAIL_VERIFICATION_SENDABLE", "boolval", null],
			"Color" => ["COLOR", "bin2hex", "hex2bin"],
			"Suspended" => ["SUSPENDED", "boolval", null],
			"Deactivated" => ["DEACTIVATED", "boolval", null],
			"ProfilePicturePath" => ["PICTURE_LOC", null, null],
			"ProfilePictureNsfw" => ["PICTURE_NSFW", "boolval", null],
			"PasswordResetToken" => ["PASSWORD_RESET_TOKEN", null, null],
		];
	}
}
